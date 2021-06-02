<p align="center">
    <a href="https://www.supportpal.com" target="_blank"><img src="https://www.supportpal.com/assets/img/logo_blue_small.png" /></a>
    <br>
    A set of CLI tools to help analyse and maintain SupportPal language files.
</p>

<p align="center">
<a href="https://github.com/supportpal/language-tools/actions"><img src="https://img.shields.io/github/workflow/status/supportpal/language-tools/ci" alt="Build Status"></a>
<a href="https://packagist.org/packages/supportpal/language-tools"><img src="https://img.shields.io/packagist/v/supportpal/language-tools" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/supportpal/language-tools"><img src="https://img.shields.io/packagist/l/supportpal/language-tools" alt="License"></a>
</p>

----

# Usage

```bash
$ composer require --dev supportpal/language-tools
```

## Compare Command

Compare your translation against the English translation files.

This will produce a diff for each file which differs from its English equivalent.

```bash 
$ php vendor/bin/language-tools compare resources/lang/en/ resources/lang/es/
# Comparing resources/lang/en/ against resources/lang/es/

```

## Sync Command

> :warning: **Experimental** :warning:
>
> Check the changes match what you expect.

Synchronise your translation with the English translation files.

This will add / remove translation strings, and also update the formatting of the file
to match the English equivalent.

```bash
$ php vendor/bin/language-tools sync resources/lang/en/ resources/lang/es/
# Synchronising resources/lang/en/ with resources/lang/es/ ...

```
