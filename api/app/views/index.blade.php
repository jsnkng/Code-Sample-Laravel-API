@extends('layouts.master')

@section('content')
<html>
<style type="text/css">
/*
 * HTML5 Boilerplate
 *
 * What follows is the result of much research on cross-browser styling.
 * Credit left inline and big thanks to Nicolas Gallagher, Jonathan Neal,
 * Kroc Camen, and the H5BP dev community and team.
 */

/* ==========================================================================
   Base styles: opinionated defaults
   ========================================================================== */

html,
button,
input,
select,
textarea {
    color: #222;
}

body {
    font-size: 1em;
    line-height: 1.4;
}

/*
 * Remove text-shadow in selection highlight: h5bp.com/i
 * These selection declarations have to be separate.
 * Customize the background color to match your design.
 */

::-moz-selection {
    background: #b3d4fc;
    text-shadow: none;
}

::selection {
    background: #b3d4fc;
    text-shadow: none;
}

/*
 * A better looking default horizontal rule
 */

hr {
    display: block;
    height: 1px;
    border: 0;
    border-top: 1px solid #ccc;
    margin: 1em 0;
    padding: 0;
}

/*
 * Remove the gap between images and the bottom of their containers: h5bp.com/i/440
 */

img {
    vertical-align: middle;
}

/*
 * Remove default fieldset styles.
 */

fieldset {
    border: 0;
    margin: 0;
    padding: 0;
}

/*
 * Allow only vertical resizing of textareas.
 */

textarea {
    resize: vertical;
}

/* ==========================================================================
   Chrome Frame prompt
   ========================================================================== */

.chromeframe {
    margin: 0.2em 0;
    background: #ccc;
    color: #000;
    padding: 0.2em 0;
}

/* ==========================================================================
   Author's custom styles
   ========================================================================== */

















/* ==========================================================================
   Helper classes
   ========================================================================== */

/*
 * Image replacement
 */

.ir {
    background-color: transparent;
    border: 0;
    overflow: hidden;
    /* IE 6/7 fallback */
    *text-indent: -9999px;
}

.ir:before {
    content: "";
    display: block;
    width: 0;
    height: 100%;
}

/*
 * Hide from both screenreaders and browsers: h5bp.com/u
 */

.hidden {
    display: none !important;
    visibility: hidden;
}

/*
 * Hide only visually, but have it available for screenreaders: h5bp.com/v
 */

.visuallyhidden {
    border: 0;
    clip: rect(0 0 0 0);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0;
    position: absolute;
    width: 1px;
}

/*
 * Extends the .visuallyhidden class to allow the element to be focusable
 * when navigated to via the keyboard: h5bp.com/p
 */

.visuallyhidden.focusable:active,
.visuallyhidden.focusable:focus {
    clip: auto;
    height: auto;
    margin: 0;
    overflow: visible;
    position: static;
    width: auto;
}

/*
 * Hide visually and from screenreaders, but maintain layout
 */

.invisible {
    visibility: hidden;
}

/*
 * Clearfix: contain floats
 *
 * For modern browsers
 * 1. The space content is one way to avoid an Opera bug when the
 *    `contenteditable` attribute is included anywhere else in the document.
 *    Otherwise it causes space to appear at the top and bottom of elements
 *    that receive the `clearfix` class.
 * 2. The use of `table` rather than `block` is only necessary if using
 *    `:before` to contain the top-margins of child elements.
 */

.clearfix:before,
.clearfix:after {
    content: " "; /* 1 */
    display: table; /* 2 */
}

.clearfix:after {
    clear: both;
}

/*
 * For IE 6/7 only
 * Include this rule to trigger hasLayout and contain floats.
 */

.clearfix {
    *zoom: 1;
}

/* ==========================================================================
   EXAMPLE Media Queries for Responsive Design.
   Theses examples override the primary ('mobile first') styles.
   Modify as content requires.
   ========================================================================== */

@media only screen and (min-width: 35em) {
    /* Style adjustments for viewports that meet the condition */
}

@media only screen and (-webkit-min-device-pixel-ratio: 1.5),
       only screen and (min-resolution: 144dpi) {
    /* Style adjustments for high resolution devices */
}

/* ==========================================================================
   Print styles.
   Inlined to avoid required HTTP connection: h5bp.com/r
   ========================================================================== */

@media print {
    * {
        background: transparent !important;
        color: #000 !important; /* Black prints faster: h5bp.com/s */
        box-shadow: none !important;
        text-shadow: none !important;
    }

    a,
    a:visited {
        text-decoration: underline;
    }

    a[href]:after {
        content: " (" attr(href) ")";
    }

    abbr[title]:after {
        content: " (" attr(title) ")";
    }

    /*
     * Don't show links for images, or javascript/internal links
     */

    .ir a:after,
    a[href^="javascript:"]:after,
    a[href^="#"]:after {
        content: "";
    }

    pre,
    blockquote {
        border: 1px solid #999;
        page-break-inside: avoid;
    }

    thead {
        display: table-header-group; /* h5bp.com/t */
    }

    tr,
    img {
        page-break-inside: avoid;
    }

    img {
        max-width: 100% !important;
    }

    @page {
        margin: 0.5cm;
    }

    p,
    h2,
    h3 {
        orphans: 3;
        widows: 3;
    }

    h2,
    h3 {
        page-break-after: avoid;
    }
}


/*! normalize.css v1.0.1 | MIT License | git.io/normalize */

/* ==========================================================================
   HTML5 display definitions
   ========================================================================== */

/*
 * Corrects `block` display not defined in IE 6/7/8/9 and Firefox 3.
 */

article,
aside,
details,
figcaption,
figure,
footer,
header,
hgroup,
nav,
section,
summary {
    display: block;
}

/*
 * Corrects `inline-block` display not defined in IE 6/7/8/9 and Firefox 3.
 */

audio,
canvas,
video {
    display: inline-block;
    *display: inline;
    *zoom: 1;
}

/*
 * Prevents modern browsers from displaying `audio` without controls.
 * Remove excess height in iOS 5 devices.
 */

audio:not([controls]) {
    display: none;
    height: 0;
}

/*
 * Addresses styling for `hidden` attribute not present in IE 7/8/9, Firefox 3,
 * and Safari 4.
 * Known issue: no IE 6 support.
 */

[hidden] {
    display: none;
}

/* ==========================================================================
   Base
   ========================================================================== */

/*
 * 1. Corrects text resizing oddly in IE 6/7 when body `font-size` is set using
 *    `em` units.
 * 2. Prevents iOS text size adjust after orientation change, without disabling
 *    user zoom.
 */

html {
    font-size: 100%; /* 1 */
    -webkit-text-size-adjust: 100%; /* 2 */
    -ms-text-size-adjust: 100%; /* 2 */
}

/*
 * Addresses `font-family` inconsistency between `textarea` and other form
 * elements.
 */

html,
button,
input,
select,
textarea {
    font-family: sans-serif;
}

/*
 * Addresses margins handled incorrectly in IE 6/7.
 */

body {
    margin: 0;
}

/* ==========================================================================
   Links
   ========================================================================== */

/*
 * Addresses `outline` inconsistency between Chrome and other browsers.
 */

a:focus {
    outline: thin dotted;
}

/*
 * Improves readability when focused and also mouse hovered in all browsers.
 */

a:active,
a:hover {
    outline: 0;
}

/* ==========================================================================
   Typography
   ========================================================================== */

/*
 * Addresses font sizes and margins set differently in IE 6/7.
 * Addresses font sizes within `section` and `article` in Firefox 4+, Safari 5,
 * and Chrome.
 */

h1 {
    font-size: 2em;
    margin: 0.67em 0;
}

h2 {
    font-size: 1.5em;
    margin: 0.83em 0;
}

h3 {
    font-size: 1.17em;
    margin: 1em 0;
}

h4 {
    font-size: 1em;
    margin: 1.33em 0;
}

h5 {
    font-size: 0.83em;
    margin: 1.67em 0;
}

h6 {
    font-size: 0.75em;
    margin: 2.33em 0;
}

/*
 * Addresses styling not present in IE 7/8/9, Safari 5, and Chrome.
 */

abbr[title] {
    border-bottom: 1px dotted;
}

/*
 * Addresses style set to `bolder` in Firefox 3+, Safari 4/5, and Chrome.
 */

b,
strong {
    font-weight: bold;
}

blockquote {
    margin: 1em 40px;
}

/*
 * Addresses styling not present in Safari 5 and Chrome.
 */

dfn {
    font-style: italic;
}

/*
 * Addresses styling not present in IE 6/7/8/9.
 */

mark {
    background: #ff0;
    color: #000;
}

/*
 * Addresses margins set differently in IE 6/7.
 */

p,
pre {
    margin: 1em 0;
}

/*
 * Corrects font family set oddly in IE 6, Safari 4/5, and Chrome.
 */

code,
kbd,
pre,
samp {
    font-family: monospace, serif;
    _font-family: 'courier new', monospace;
    font-size: 1em;
}

/*
 * Improves readability of pre-formatted text in all browsers.
 */

pre {
    white-space: pre;
    white-space: pre-wrap;
    word-wrap: break-word;
}

/*
 * Addresses CSS quotes not supported in IE 6/7.
 */

q {
    quotes: none;
}

/*
 * Addresses `quotes` property not supported in Safari 4.
 */

q:before,
q:after {
    content: '';
    content: none;
}

/*
 * Addresses inconsistent and variable font size in all browsers.
 */

small {
    font-size: 80%;
}

/*
 * Prevents `sub` and `sup` affecting `line-height` in all browsers.
 */

sub,
sup {
    font-size: 75%;
    line-height: 0;
    position: relative;
    vertical-align: baseline;
}

sup {
    top: -0.5em;
}

sub {
    bottom: -0.25em;
}

/* ==========================================================================
   Lists
   ========================================================================== */

/*
 * Addresses margins set differently in IE 6/7.
 */

dl,
menu,
ol,
ul {
    margin: 1em 0;
}

dd {
    margin: 0 0 0 40px;
}

/*
 * Addresses paddings set differently in IE 6/7.
 */

menu,
ol,
ul {
    padding: 0 0 0 40px;
}

/*
 * Corrects list images handled incorrectly in IE 7.
 */

nav ul,
nav ol {
    list-style: none;
    list-style-image: none;
}

/* ==========================================================================
   Embedded content
   ========================================================================== */

/*
 * 1. Removes border when inside `a` element in IE 6/7/8/9 and Firefox 3.
 * 2. Improves image quality when scaled in IE 7.
 */

img {
    border: 0; /* 1 */
    -ms-interpolation-mode: bicubic; /* 2 */
}

/*
 * Corrects overflow displayed oddly in IE 9.
 */

svg:not(:root) {
    overflow: hidden;
}

/* ==========================================================================
   Figures
   ========================================================================== */

/*
 * Addresses margin not present in IE 6/7/8/9, Safari 5, and Opera 11.
 */

figure {
    margin: 0;
}

/* ==========================================================================
   Forms
   ========================================================================== */

/*
 * Corrects margin displayed oddly in IE 6/7.
 */

form {
    margin: 0;
}

/*
 * Define consistent border, margin, and padding.
 */

fieldset {
    border: 1px solid #c0c0c0;
    margin: 0 2px;
    padding: 0.35em 0.625em 0.75em;
}

/*
 * 1. Corrects color not being inherited in IE 6/7/8/9.
 * 2. Corrects text not wrapping in Firefox 3.
 * 3. Corrects alignment displayed oddly in IE 6/7.
 */

legend {
    border: 0; /* 1 */
    padding: 0;
    white-space: normal; /* 2 */
    *margin-left: -7px; /* 3 */
}

/*
 * 1. Corrects font size not being inherited in all browsers.
 * 2. Addresses margins set differently in IE 6/7, Firefox 3+, Safari 5,
 *    and Chrome.
 * 3. Improves appearance and consistency in all browsers.
 */

button,
input,
select,
textarea {
    font-size: 100%; /* 1 */
    margin: 0; /* 2 */
    vertical-align: baseline; /* 3 */
    *vertical-align: middle; /* 3 */
}

/*
 * Addresses Firefox 3+ setting `line-height` on `input` using `!important` in
 * the UA stylesheet.
 */

button,
input {
    line-height: normal;
}

/*
 * 1. Avoid the WebKit bug in Android 4.0.* where (2) destroys native `audio`
 *    and `video` controls.
 * 2. Corrects inability to style clickable `input` types in iOS.
 * 3. Improves usability and consistency of cursor style between image-type
 *    `input` and others.
 * 4. Removes inner spacing in IE 7 without affecting normal text inputs.
 *    Known issue: inner spacing remains in IE 6.
 */

button,
html input[type="button"], /* 1 */
input[type="reset"],
input[type="submit"] {
    -webkit-appearance: button; /* 2 */
    cursor: pointer; /* 3 */
    *overflow: visible;  /* 4 */
}

/*
 * Re-set default cursor for disabled elements.
 */

button[disabled],
input[disabled] {
    cursor: default;
}

/*
 * 1. Addresses box sizing set to content-box in IE 8/9.
 * 2. Removes excess padding in IE 8/9.
 * 3. Removes excess padding in IE 7.
 *    Known issue: excess padding remains in IE 6.
 */

input[type="checkbox"],
input[type="radio"] {
    box-sizing: border-box; /* 1 */
    padding: 0; /* 2 */
    *height: 13px; /* 3 */
    *width: 13px; /* 3 */
}

/*
 * 1. Addresses `appearance` set to `searchfield` in Safari 5 and Chrome.
 * 2. Addresses `box-sizing` set to `border-box` in Safari 5 and Chrome
 *    (include `-moz` to future-proof).
 */

input[type="search"] {
    -webkit-appearance: textfield; /* 1 */
    -moz-box-sizing: content-box;
    -webkit-box-sizing: content-box; /* 2 */
    box-sizing: content-box;
}

/*
 * Removes inner padding and search cancel button in Safari 5 and Chrome
 * on OS X.
 */

input[type="search"]::-webkit-search-cancel-button,
input[type="search"]::-webkit-search-decoration {
    -webkit-appearance: none;
}

/*
 * Removes inner padding and border in Firefox 3+.
 */

button::-moz-focus-inner,
input::-moz-focus-inner {
    border: 0;
    padding: 0;
}

/*
 * 1. Removes default vertical scrollbar in IE 6/7/8/9.
 * 2. Improves readability and alignment in all browsers.
 */

textarea {
    overflow: auto; /* 1 */
    vertical-align: top; /* 2 */
}

/* ==========================================================================
   Tables
   ========================================================================== */

/*
 * Remove most spacing between table cells.
 */

table {
    border-collapse: collapse;
    border-spacing: 0;
}

button {
    margin:10px;
    width:240px;
    }
.stable {
    -moz-box-shadow:inset 0px 1px 0px 0px #bbdaf7;
    -webkit-box-shadow:inset 0px 1px 0px 0px #bbdaf7;
    box-shadow:inset 0px 1px 0px 0px #bbdaf7;
    background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #79bbff), color-stop(1, #378de5) );
    background:-moz-linear-gradient( center top, #79bbff 5%, #378de5 100% );
    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#79bbff', endColorstr='#378de5');
    background-color:#79bbff;
    -moz-border-radius:16px;
    -webkit-border-radius:16px;
    border-radius:16px;
    border:1px solid #84bbf3;
    display:inline-block;
    color:#ffffff;
    font-family:Arial;
    font-size:14px;
    font-weight:bold;
    padding:6px 21px;
    text-decoration:none;
    text-shadow:1px 1px 0px #528ecc;
}.stable:hover {
    background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #378de5), color-stop(1, #79bbff) );
    background:-moz-linear-gradient( center top, #378de5 5%, #79bbff 100% );
    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#378de5', endColorstr='#79bbff');
    background-color:#378de5;
}.stable:active {
    position:relative;
    top:1px;

}
.dev {
    -moz-box-shadow:inset 0px 1px 0px 0px #ffffff;
    -webkit-box-shadow:inset 0px 1px 0px 0px #ffffff;
    box-shadow:inset 0px 1px 0px 0px #ffffff;
    background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #f9f9f9), color-stop(1, #e9e9e9) );
    background:-moz-linear-gradient( center top, #f9f9f9 5%, #e9e9e9 100% );
    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#f9f9f9', endColorstr='#e9e9e9');
    background-color:#f9f9f9;
    -moz-border-radius:16px;
    -webkit-border-radius:16px;
    border-radius:16px;
    border:1px solid #dcdcdc;
    display:inline-block;
    color:#666666;
    font-family:Arial;
    font-size:14px;
    font-weight:bold;
    padding:6px 21px;
    text-decoration:none;
    text-shadow:1px 1px 0px #ffffff;
}.dev:hover {
    background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #e9e9e9), color-stop(1, #f9f9f9) );
    background:-moz-linear-gradient( center top, #e9e9e9 5%, #f9f9f9 100% );
    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#e9e9e9', endColorstr='#f9f9f9');
    background-color:#e9e9e9;
}.dev:active {
    position:relative;
    top:1px;
}

.std {
    -moz-box-shadow:inset 0px 1px 0px 0px #fbafe3;
    -webkit-box-shadow:inset 0px 1px 0px 0px #fbafe3;
    box-shadow:inset 0px 1px 0px 0px #fbafe3;
    background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #ff5bb0), color-stop(1, #ef027d) );
    background:-moz-linear-gradient( center top, #ff5bb0 5%, #ef027d 100% );
    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff5bb0', endColorstr='#ef027d');
    background-color:#ff5bb0;
    -moz-border-radius:16px;
    -webkit-border-radius:16px;
    border-radius:16px;
    border:1px solid #ee1eb5;
    display:inline-block;
    color:#ffffff;
    font-family:Arial;
    font-size:14px;
    font-weight:bold;
    padding:6px 21px;
    text-decoration:none;
    text-shadow:1px 1px 0px #c70067;
}.std:hover {
    background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #ef027d), color-stop(1, #ff5bb0) );
    background:-moz-linear-gradient( center top, #ef027d 5%, #ff5bb0 100% );
    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ef027d', endColorstr='#ff5bb0');
    background-color:#ef027d;
}.std:active {
    position:relative;
    top:1px;
}
/* This imageless css button was generated by CSSButtonGenerator.com */

h1,
h2,
h3 {
    text-align:center;
    margin:10px;
    width:240px;
    color:#666666;
    font-family:Arial;
    font-size:21px;
    font-weight:bold;
    text-decoration:none;
    text-shadow:1px 1px 0px #ffffff;

}
fieldset {
    -moz-box-shadow:inset 0px 1px 0px 0px #ffffff;
    -webkit-box-shadow:inset 0px 1px 0px 0px #ffffff;
    box-shadow:inset 0px 1px 0px 0px #ffffff;
    background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #f9f9f9), color-stop(1, #e9e9e9) );
    background:-moz-linear-gradient( center top, #f9f9f9 5%, #e9e9e9 100% );
    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#f9f9f9', endColorstr='#e9e9e9');
    background-color:#f9f9f9;
    -moz-border-radius:16px;
    -webkit-border-radius:16px;
    border-radius:16px;
    border:1px solid #dcdcdc;
    display:inline-block;


}
</style>
<body>
<div style="width:320px; margin:0 auto;">
<fieldset>
<h2>Electronic Transfer of Care (ETOC)</h2>
<button type="button" class="dev"  onClick="location='/api/get_etoc.html';">get_etoc.html</button><br />
<button type="button" class="dev"  onClick="location='/api/post_etoc';">post_etoc</button><br />
<button type="button" class="dev"  onClick="location='/api/get_etoc_request_pdf.html';">get_etoc_request_pdf.html</button><br />
<button type="button" class="dev"  onClick="location='/api/post_etoc_request_pdf';">post_etoc_request_pdf</button><br />
<button type="button" class="dev"  onClick="location='/api/post_etoc_fax_disposition';">post_etoc_fax_disposition</button><br />
</fieldset>
<br />
<br />

<fieldset>
<h2>Electronic Patient Authorization Form (EPAF)</h2>
<button type="button" class="dev"  onClick="location='/api/get_epaf.html';">get_epaf.html</button><br />
<button type="button" class="dev"  onClick="location='/api/post_epaf';">post_epaf</button><br />
<button type="button" class="dev"  onClick="location='/api/get_epaf_request_pdf.html';">get_epaf_request_pdf.html</button><br />
<button type="button" class="dev"  onClick="location='/api/post_epaf_request_pdf';">post_epaf_request_pdf</button><br />
<button type="button" class="dev"  onClick="location='/api/post_epaf_fax_disposition';">post_epaf_fax_disposition</button><br />
</fieldset>
</div>

    </body>
    </html>
    @stop
