import tseslint from 'typescript-eslint'

export default tseslint.config(
  { ignores: ['public/**', 'node_modules/**', 'vendor/**'] },
  ...tseslint.configs.recommended,
  { files: ['resources/js/**/*.ts'], rules: { '@typescript-eslint/no-unused-vars': ['error', { argsIgnorePattern: '^_', varsIgnorePattern: '^_' }] } },
)
