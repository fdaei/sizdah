import pluginVue from 'eslint-plugin-vue'
import { defineConfigWithVueTs, vueTsConfigs } from '@vue/eslint-config-typescript'

/**
 * ESLint 10 flat config.
 *
 * The `lint` script has always referenced a config that was never committed, so
 * `npm run lint` failed outright and CLAUDE.md recorded it as "broken — do not
 * treat lint as a gate". This is that missing file. The script also carried
 * `--ext`, removed in ESLint 9; flat config derives extensions from the `files`
 * globs instead, so the flag is gone from package.json too.
 *
 * Scope is deliberately narrow: `resources/js` only. Build output, the Vite and
 * Tailwind configs, and vendor code are not linted.
 */
export default defineConfigWithVueTs(
  {
    name: 'sizdah/files',
    files: ['resources/js/**/*.{ts,vue}'],
  },

  {
    name: 'sizdah/ignores',
    ignores: ['public/build/**', 'node_modules/**', 'vendor/**'],
  },

  pluginVue.configs['flat/recommended'],
  vueTsConfigs.recommended,

  {
    name: 'sizdah/rules',
    rules: {
      /*
       | Pages and components are single-file and named by their route, so the
       | multi-word rule fights the framework rather than helping: `About.vue`,
       | `Home.vue`, `Contact.vue` are all correct here.
       */
      'vue/multi-word-component-names': 'off',

      /*
       | Admin-authored rich text is rendered with v-html in three places
       | (Legal, About's story, the article body). Each carries an inline
       | eslint-disable with a reason; this keeps the rule on so a fourth one
       | has to justify itself.
       */
      'vue/no-v-html': 'warn',

      '@typescript-eslint/no-unused-vars': [
        'error',
        { argsIgnorePattern: '^_', varsIgnorePattern: '^_' },
      ],

      /*
       | Formatting is Prettier's job (`npm run format`). These three rules
       | account for 318 of the 321 findings on the current tree and every one
       | of them is a disagreement about where Prettier put a line break, not a
       | defect. Turning them off keeps lint a correctness gate; without this
       | the real findings are buried.
       */
      'vue/max-attributes-per-line': 'off',
      'vue/singleline-html-element-content-newline': 'off',
      'vue/html-self-closing': 'off',
    },
  },
)
