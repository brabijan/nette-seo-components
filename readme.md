# SEO components

This is seo components for [Nette Framework](http://nette.org/). It's allows you control these things:
 
- Google analytics
- Google webmaster tools
- Auto generated sitemap.xml
- robots.txt
- meta tags (title, keywords, description and robots)
- SEO friendly URLs

## Instalation

The best way to install brabijan/nette-seo-components is using  [Composer](http://getcomposer.org/):


```sh
$ composer require brabijan/nette-seo-components:@dev
```

Then you have to register extension in `config.neon`.

```yaml
extensions:
	- Brabijan\SeoComponents\DI\SeoExtension
```
