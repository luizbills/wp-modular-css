# JSON config file properties

## Options

### `prefix`

default value: `null`

Adds a prefix in all tachyons classes.

### `debug`

default value: `false`

Adds the [**debug** module](https://github.com/luizbills/wp-modular-css/blob/master/css-modules/tachyons-default/debug.css).

### `debug-children`

default value: `false`

Adds the [**debug-children** module](https://github.com/luizbills/wp-modular-css/blob/master/css-modules/tachyons-default/debug-children.css).

### `debug-grid`

default value: `false`

Adds the [**debug-grid** module](https://github.com/luizbills/wp-modular-css/blob/master/css-modules/tachyons-default/debug-grid.css).

### `media-queries`

Defines your **breakpoints** to responsive classes. If no breakpoints are set, then no module will be responsive.

### `colors`

The colors used in several modules, like "text colors", "background colors", "border colors", ...

### `__enabled-modules`

defines which tachyons modules will be used, which ones will be responsive, and their order in the generated css file.

#### How to make a module responsive or non-responsive

Put `"responsive"` inside of the module's array definition.

E.g.: make "border-colors" module responsive (which by default is not responsive).
```json
{
	"__enabled-modules": {

		"border-colors": [ "responsive" ]

	}
}
```

### Other details

The other options are used to customize all modules.

#### `@default`

Some options has `"@default"` as name, this means an *empty* name.

E.g.: .
```json
{
	"letter-spacing": {
		"@default": ".1em",
		"tight":    "-.05em",
		"mega":     ".25em"
	}
}
```

the above config generates:
```css
.tracked { letter-spacing: .1em; }
.tracked-tight { letter-spacing: -.05em; }
.tracked-mega { letter-spacing: .25em; }
```

#### `[[@use ...]]`

Some options has `"[[@use ...]]"` as value, this is used to get value from other options.
```json
{
	"colors": {
		"crazy-color": "#123456"
	},
	"nested": {
		"links-color": "[[@use colors crazy-color]]"
	}
}
```

The above config generates:
```css
.nested-links a {
	color: #123456;
	...
}
```
