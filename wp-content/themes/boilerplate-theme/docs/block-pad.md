# Block Pad Classes

Vertical spacing classes used for most blocks across the site.    
Adds vertical space padding to the top and bottom of the element.

Exists on *most* blocks by default...
```
.block-pad {
  padding-top: var(--l-block-pad);
  padding-bottom: var(--l-block-pad);
}
```

## Overrides.
In the CMS use the Advanced option on each block to add your own classes.

Use the following classes to collapse spacing where necessary.   
You can mix, match, combine classes as much as you like.

Priority will be as follows, with first being lowest priority, and last being highest priority.
```
.block-pad--top {
  padding-top: var(--l-block-pad);
}
  
.block-pad--bottom {
  padding-bottom: var(--l-block-pad);
}
  
.block-pad--top-0 {
  padding-top: 0;
}
  
.block-pad--bottom-0 {
  padding-bottom: 0;
}
  
.block-pad--top-half {
  padding-top: calc(0.5 * var(--l-block-pad));
}
  
.block-pad--bottom-half {
  padding-bottom: calc(0.5 * var(--l-block-pad));
}
  
.block-pad--top-quarter {
  padding-top: calc(0.25 * var(--l-block-pad));
}
  
.block-pad--bottom-quarter {
  padding-bottom: calc(0.25 * var(--l-block-pad));
}
```
