<!--header-->
<!-- Auto generated do not modify between `header` and `/header` -->

# <a href="http://maslosoft.com/addendum/">Maslosoft Addendum</a>
<a href="http://maslosoft.com/addendum/">_Easy to use php annotations_</a>

<a href="https://packagist.org/packages/maslosoft/addendum" title="Latest Stable Version">
<img src="https://poser.pugx.org/maslosoft/addendum/v/stable.svg" alt="Latest Stable Version" style="height: 20px;"/>
</a>
<a href="https://packagist.org/packages/maslosoft/addendum" title="License">
<img src="https://poser.pugx.org/maslosoft/addendum/license.svg" alt="License" style="height: 20px;"/>
</a>
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Maslosoft/Addendum/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Maslosoft/Addendum/?branch=master)
<img src="https://travis-ci.org/Maslosoft/Addendum.svg?branch=master"/>
<img src="https://travis-ci.org/Maslosoft/Addendum.svg?branch=master"/>

### Quick Install
```bash
composer require maslosoft/addendum:"*"
```

<!--/header-->

Goal of this extension is to provide additional metadata for any class, their properties or methods.
It's based on annotations, so adding this metadata is as easy as adding doc comments. This is build on top of [addendum](http://code.google.com/p/addendum/) php library

##Background

I created this extension, because I wanted to see all attribute properties of my models in one place and also needed more than Yii built in. I mean like validators, labels. This way i have property of model, and just above it i have all what it defines. Some models even does not require any methods, plain attributes with annotations.

##Key features

* Easy add metadata
* Lightweight container
* Extendable
* Netbeans completition support

##Setup
Use composer to install extension:

	composer require maslosoft/addendum:"*"

## Yii integration
	
Addendum uses EmbeDi for configuration, so configure and preload embedi too:
```php
'preload' => ['embedi'],
'components' => [
	'embedi' => [
		'class' => \Maslosoft\EmbeDi\Adapters\YiiEmbeDi::class
	],
	'addendum' => [
		'class' => Maslosoft\Addendum\Addendum::class,
	],
]
```

##Basic usage

**This only in short summary, please refer to [full yii addendum documentation](http://maslosoft.com/en/open-source/addendum/) or docs folder of this exteion for detailed explantion and [php addendum documentation](http://code.google.com/p/addendum/w/list) for in-depth annotations docs.**

###What are annotations

*If you are familiar with annotations, skip this chapter.*

Annotations are special type of comments, which are parsed and evaluated by annotaion engine. They are similar to php doc tag, but to distinguish they starts with capital letter. They also can have optional params or even can be nested. Simple example:
```php
/**
 * @Label('First name')
 * @Persistent
 * @RequiredValidator
 * @var string
 */
$name = '';
```
`@var` is well known doc tag, while `@Label` defines label for this attribute. `@Persistent` indicates that attribute is persistent and `@RequiredValidator` *might* add built in `required` validator. Notice the word might, as annotations are not classes, what they do can be defined elsewhere. For detailed annotations syntax please visit [addendum documentation](http://code.google.com/p/addendum/wiki/ShortTutorialByExample).

###Using annotations in your application

First of all, if you want your class to be used by addendum engine, you have to "implement" IAnnotated interface. Implement is a big word here, as it is just empty interface, used only to allow annotating class.

Now you can add annotations like in example below:

```php
/**
 * @Label('My application user')
 */
class User extends CActiveRecord implements IAnnotated
{
	/**
	 * @Label('First name')
	 * @RequiredValidator
	 * @var string
	 */
	$name = '';
}
```

Now you have some annotation added. Each annotation defines some metadadata for it's **entity** - using entity i will refer to one of **class**, **property** or **method**.

Next we can scrap that metadata from class definition, and put it to lightweight container.
```php
$meta = Yii::app()->addendum->meta(new User);
// You can also create container directly
// $meta = EComponentMeta::create(new User); - this will return the same as above
echo $meta->type()->label;
echo $meta->name->label;
```

This will output `My application user` `First name`.
`$meta->type()` gets class (type) metadata, while class properties are available as fields, and methods with `$meta->method('methodName')` function.

NOTE: setting `@Label` does **not** mean that `label` field will be set in container - it is annotation responsibility of what to do with it's data.

###Defining annotation

Creating your own annotation is very easy. Ill demonstrate it on `@Label`. Just create class with `Annotation` suffix, and make sure it is imported.

```php
<?php

/**
 * Label
 * Set translated entity 'label' field
 * @template Label('${text}')
 */
class LabelAnnotation extends EComponentMetaAnnotation
{
	public $value = '';

	public function init()
	{
		$this->value = Yii::t('', $this->value);

		$this->_entity->label = $this->value;
	}

	public function __toString()
	{
		return $this->value;
	}
}
```

Here special property `_entity` have set `label` field. `_entity` is container for class (type), field or method, depending on context. 

NOTE: `@template` is special docblock used to generate netbeans completition files.


##Requirements
* PHP 5.5

##Known bugs
* https://github.com/Maslosoft/Addendum/issues/8

##Resources
* [Project page](http://maslosoft.com/addendum/)
* [Project on github](https://github.com/Maslosoft/Addendum)
* [Report a bug or request feature](https://github.com/Maslosoft/Addendum/issues)
* [PHP Addendum library](http://code.google.com/p/addendum/)

##Big thanks goes to
* Jan Suchal for creating php addendum
* be next here:)
