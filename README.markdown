# Morf - yet another form library for Kohana PHP

This is the active development tree for the Morf form module for [Kohana](http://www.kohanaphp.com)

## What is Morf?

Morf is a form creation library for Kohana. It has been designed to ease the generation and rendering of HTML forms from a controller, library or helper within Kohana.

Unlike Forge, Morf does not provide any inbuilt validation. All validation should be handled by the [Validation](http://docs.kohanaphp.com/libraries/validation) library included with the standard Kohana distribution.

By using standard existing Kohana helpers and libraries, Morf is highly customisable and extendable, plus supports i18n internationalisation.

## Features

 	-	Supports common form elements, including standard inputs, text areas. buttons, select lists, radio groups, ratings and fieldsets
 	-	Highly customisable templating system using Kohana's view library. Output your elements any way you like
 	-	Chainable element creation
 	-	Plain text elements supported for insertion of custom HTML code anywhere within the form structure
 	-	Supports HTML5 data attributes
 	-	Supports the Kohana Validation library
 	-	Posted form values can be auto-filled on reload, with full control over which fields auto-populate.
 	-	Auto detects absence of a submit button and appends one to the end automatically
 	-	Auto detects file input elements and switches form enctype attribute accordingly
 	-	Morf forms can be passed directly to the view as an object
 	-	Full i18n support for default form values

## What is in a name?

It does not take a genius to realise that the name 'Morf' was derived from 'form'. However greater inspiration cames from [Morph](http://uk.youtube.com/watch?v=jSMRPKM1evk), a personal favourite animated character who regularly appeared on the late Tony Heart's childrens television programmes.

## Git rather than SVN?

I have chosen to host this project on Github rather than Google Code. The reason for this is simple; I like Git. Unlike SVN, Git is distributed rather than centralised. [Shadowhand](http://github.com/shadowhand) has created a very useful explanation of how to use Git with Kohana projects [here](http://github.com/kohana/kohana/tree/master/README.markdown).

## How to get Morf

If you have Git installed on your system, the easiest way to get Morf is to use the command line. First navigate to your Kohana modules folder, then enter :

	git clone git://github.com/samsoir/morf.git

This is the equivalent to svn checkout url.

To update your local copy when changes occur, in your local Kohana modules folder enter :

	git pull morf

Alternatively you can download a zip or tar file directly from Github, just select the download button and choose your desired format. The downside of this is that you will manually have to update your local copy.

## How to use Morf

Full documentation will be provided soon.

## Author

Morf has been created by [Sam Clark](http://sam.clark.name) of [Polaris Digital](http://polaris-digital.com).

## License

Morf Copyright (c) 2008-2009 Polaris Digital

Morf is licensed under the [General Public License version 3](http://www.gnu.org/licenses/gpl-3.0.html)