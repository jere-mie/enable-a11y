<p>
  Focus states are used by keyboard users to know what interactive element they can currently manipulate. They are
  usually styled with

<h2>Focus Styling For Keyboard Users Only</h2>


<?php includeStats(array('isForNewBuilds' => true, 'comment' => 'This is recommended for use in both new and existing projects.  It ')) ?>


<p>
  When I am auditing a website for accessibility issues for the first time, lack of focus indicators is usually one of
  the first things I see. This is usually because a lot of designers and/or developers will think that focus indicators
  look ugly and will put in the following CSS to get rid of them:
</p>
<figure class="wide">


  <?php includeShowcode("focus-remove", "", "", "", false)?>

  <figcaption>Figure 1. Horrible code a lot of developers use to turn off focus states.  Never do this.</figcaption>


</figure>
<script type="application/json" id="focus-remove-props">
{
  "replaceHtmlRules": {},
  "steps": [{
    "label": "Remove focus indicator CSS",
    "highlight": "%INLINE%focus-remove",
    "notes": ""
  }]
}
</script>

<template id="focus-remove" data-type="css">
  *:focus {  outline: none;  }
</template>

<p>
  <strong>This is a bad idea.</strong> Keyboard users need focus states need these focus indicators to know what
  interactive element currently has focus. "But VoiceOver has it's own focus indicator!" is what I hear some of you say.
  Not everyone who uses a keyboard uses VoiceOver. <strong>You absolutely need a visible focus indicator on all your
    interactive elements in order to pass <a href="https://www.w3.org/WAI/WCAG21/Understanding/focus-visible.html">WCAG
      2.4.7</a></strong>.
</p>
<p>
  <strong>What you can do is make focus indicators only appear for keyboard users.</strong> This can be done using the
  <code>:focus-visible</code> CSS pseudo-class. Here is how the Enable site codes them globally using <a
    href="https://www.tpgi.com/focus-visible-and-backwards-compatibility/">TPGI's excellent method to use
    <code>:focus-visible</code> while ensuring browsers that don't support it fallback to using <code>:focus</code>
    gracefully</a>:
</p>

<figure class="wide">
  <?php includeShowcode("css-focus-visible", "", "", "", false)?>

  <figcaption>Figure 2. Much better code that styles focus states for keyboard users, while minimizing its visibility for mouse users.</figcaption>
</figure>

<script type="application/json" id="css-focus-visible-props">
{
  "replaceHtmlRules": {},
  "steps": [{
    "label": "Focus Visible CSS recipe",
    "highlight": "%INLINE%css-focus-visible",
    "notes": ""
  }]
}
</script>

<template id="css-focus-visible" data-type="css">
  /* Initial focus style for all browsers (keyboard and mouse users). */
  *:focus { outline: solid 2px #3b99fc; }

  /* Turn off focus style above if browser supports :focus-visible. */
  *:focus:not(:focus-visible) { outline: none; }

  /*
  * For browsers that support :focus-visible, use it to show focus
  * indicators to keyboard users only.
  */
  *:focus-visible { outline: solid 2px #3b99fc; }

</template>

<p>
  Is it just keyboard users that will see focus states styled with <code>focus-visible</code>. Kind of, but there are a
  few subtleties. <a href="https://andyadams.org/">Andy Adams</a> has written <a
    href="https://css-tricks.com/almanac/selectors/f/focus-visible/">a great article for CSS Tricks about
    :focus-visible</a> that really goes into detail.
</p>

<h2>Increase Hit Areas Inside Focusable Elements</h2>

<p>
  If you use a keyboard to navigate through the main navigation, you will notice the clickable hit-area of the top level
  navigation items are a lot bigger than they actually take up in the layout:
</p>


<figure>

  <?php pictureWebpPng("images/focus/clickable-hit-state", "Screenshot of the Enable website's main navigation, with keyboard focus applied to the 'controls' navigation drawer.")?>

  <figcaption>Figure 3. The focus state of the "Contols" navigation button. Note that the hit area is a lot larger than
    the visual height of the thin horizontal gray strip where the drawer sits inside.</figcaption>
</figure>

<p>We increased the hit area to conform to <a href="https://www.w3.org/WAI/WCAG21/Understanding/target-size.html">WCAG
    2.5.5: Target Size</a>. Even though this is a AAA requirement, it is so easiy to implement by increasing the padding
  and componsating visually with an equivalent negative margin:</p>

<?php includeShowcode("css-focus-hitarea", "", "", "", false)?>
<script type="application/json" id="css-focus-hitarea-props">
{
  "replaceHtmlRules": {},
  "steps": [{
    "label": "Focus Visible CSS recipe",
    "highlight": "%INLINE%css-focus-hitarea",
    "notes": ""
  }]
}
</script>

<template id="css-focus-hitarea" data-type="css">
  .enable-flyout__open-level-button { padding: 27px 0; margin: -27px 0; }

</template>

<p>I encourage everyone reading this to implement this on all the websites they code. From a UX perspective, it just
  makes it easier for everyone to use the websites you code.</p>

<h2>Issues with CSS Transitions and CSS outline in Safari</h2>

<p>
  On a few projects, I have noticed that Safari focus states don't appear correctly when the element that is focused has the following CSS applied to it:
</p>

<figure class="wide">
  <?php includeShowcode("transition-all-code", "", "", "", false)?>

  <figcaption>Figure 4. CSS <strong>transition: all</strong> code that should be avoided.</figcaption>
</figure>

<script type="application/json" id="transition-all-code-props">
{
  "replaceHtmlRules": {},
  "steps": [{
    "label": "Bad transition all CSS code that should be avoided.",
    "highlight": "%INLINE%transition-all-code",
    "notes": ""
  }]
}
</script>

<template id="transition-all-code" data-type="css">
  a {
    transition: all 0.3s ease-in-out;
  }
</template>

<p>
  The above CSS can mess up Safari focus states: they may appear cut off or may not appear at all in Safari, while they may appear fine in other web browsers.  <strong>The correct way to fix this is to <em>never</em> use <code> transition: all</code> in your CSS.</strong> Using <code>all</code>.  There are many reasons why you should never use not use the <code>all</code> keyword for transitions (in this case, because of unwanted side-effects, but also for performance reasons).  <a href="https://www.pno.dev/"></a> has written a great write-up on <a href="https://www.pno.dev/articles/dont-use-the-all-keyword-in-css-transitions/">why you shouldn't use the 'all' keyword in CSS transitions</a>, and I suggest all developers read this.
</p>

<p>
  If removing the <code>all</code> transition code will cause problems in your project, you can use the following hack to fix the code in Safari:
</p>

<figure class="wide">
  <?php includeShowcode("fix-transition-all-code", "", "", "", false)?>

  <figcaption>Figure 5. Fix for Safari to work around <strong>transition: all</strong> code issue.</figcaption>
</figure>

<script type="application/json" id="fix-transition-all-code-props">
{
  "replaceHtmlRules": {},
  "steps": [{
    "label": "Fix for Safari.",
    "highlight": "%INLINE%fix-transition-all-code",
    "notes": ""
  }]
}
</script>

<template id="fix-transition-all-code" data-type="css">
@media screen and (-webkit-min-device-pixel-ratio:0) {

  /* Safari only*/
  *:focus { transition: none !important; }

}
</template>


<p>
  Note that <strong>it is much better to remove the <code>all</code> keyword and just transtition what you need instead.</strong>  This solution should only be a band-aid solution until you can fix the issue properly.
</p>


<h2>Don't Forget Windows High Contrast Mode Users.</h2>

Sometimes, you will want to style focus states without the CSS <code>outline</code> property.  If you do this, but instead of using <code>outline: none</code> to remove the default focus ring, developers should use outline with the <code>transparent</code> colour:


<figure class="wide">
  <?php includeShowcode("transparent-outline-code", "", "", "", false)?>

  <figcaption>Figure 6. Adding a transparent outline along with your custom focus state that doesn't have an outline</figcaption>
</figure>

<script type="application/json" id="transparent-outline-code-props">
{
  "replaceHtmlRules": {},
  "steps": [{
    "label": "Transparent outline fix",
    "highlight": "%INLINE%transparent-outline-code",
    "notes": ""
  }]
}
</script>

<template id="transparent-outline-code" data-type="css">
button.special-style:focus { outline: transparent 2px solid; border-bottom: 2px solid #00f; }
</template>