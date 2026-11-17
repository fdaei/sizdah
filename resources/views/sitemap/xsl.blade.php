<?php echo '<?xml version="1.0" encoding="UTF-8"?>'."\n"; ?>
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:s="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xhtml="http://www.w3.org/1999/xhtml"
    xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
    exclude-result-prefixes="s xhtml image">
<xsl:output method="html" encoding="UTF-8" indent="yes"/>
<xsl:template match="/">
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8"/>
<meta name="robots" content="noindex,follow"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<title>نقشه سایت XML — {{ config('app.name') }}</title>
<style>
body{font-family:Tahoma,system-ui,sans-serif;margin:0;background:#f7f7f8;color:#1f2937}
.wrap{max-width:1100px;margin:0 auto;padding:24px 16px}
h1{font-size:22px;margin:0 0 6px}
p.info{color:#6b7280;font-size:13px;margin:0 0 20px;line-height:1.8}
table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.08);font-size:13px}
th{background:#111827;color:#fff;text-align:start;padding:10px 12px;font-weight:600}
td{padding:9px 12px;border-top:1px solid #eef0f3;vertical-align:top}
tr:hover td{background:#fafafa}
a{color:#2563eb;text-decoration:none;direction:ltr;unicode-bidi:embed;word-break:break-all}
td.num{direction:ltr;text-align:center;white-space:nowrap;color:#374151}
</style>
</head>
<body><div class="wrap">
<h1>نقشه سایت XML</h1>
<xsl:choose>
<xsl:when test="s:sitemapindex">
<p class="info">این فهرست <xsl:value-of select="count(s:sitemapindex/s:sitemap)"/> نقشه‌ی سایت دارد. این صفحه برای موتورهای جستجو (گوگل، بینگ و…) ساخته شده؛ آدرس <b>/sitemap.xml</b> را در Google Search Console ثبت کنید.</p>
<table><tr><th>نقشه سایت</th><th>آخرین تغییر</th></tr>
<xsl:for-each select="s:sitemapindex/s:sitemap">
<tr><td><a href="{s:loc}"><xsl:value-of select="s:loc"/></a></td><td class="num"><xsl:value-of select="concat(substring(s:lastmod,1,10),' ',substring(s:lastmod,12,5))"/></td></tr>
</xsl:for-each></table>
</xsl:when>
<xsl:otherwise>
<p class="info">این نقشه سایت <xsl:value-of select="count(s:urlset/s:url)"/> آدرس دارد. <a href="{{ route('sitemap') }}">← بازگشت به فهرست نقشه‌ها</a></p>
<table><tr><th>آدرس</th><th>تصاویر</th><th>اولویت</th><th>تناوب</th><th>آخرین تغییر</th></tr>
<xsl:for-each select="s:urlset/s:url">
<tr><td><a href="{s:loc}"><xsl:value-of select="s:loc"/></a></td>
<td class="num"><xsl:value-of select="count(image:image)"/></td>
<td class="num"><xsl:value-of select="s:priority"/></td>
<td class="num"><xsl:value-of select="s:changefreq"/></td>
<td class="num"><xsl:value-of select="concat(substring(s:lastmod,1,10),' ',substring(s:lastmod,12,5))"/></td></tr>
</xsl:for-each></table>
</xsl:otherwise>
</xsl:choose>
</div></body></html>
</xsl:template>
</xsl:stylesheet>
