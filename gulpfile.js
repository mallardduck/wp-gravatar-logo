/**
 * Gulpfile.
 * Project Configuration for gulp tasks.
 */

var pkg                     	= require('./package.json');
var projectURL              	= 'http://wp-avatar-logo.dev/wp-admin/customize.php';

var styleFrontendSRC  		= './assets/scss/wp-avatar-logo-frontend.scss'; // Path to frontend .scss file.
var styleRangeControlSRC  	= './assets/scss/wp-avatar-logo-range-control.scss'; // Path to Customizer range control .scss file.

var styleDestination  		= './assets/css/'; // Path to place the compiled CSS file.
var styleWatchFiles   		= './assets/scss/**/*.scss'; // Path to all *.scss files inside css folder and inside them.

var scriptCustomizePreviewFile  = 'wp-avatar-logo-customize-preview'; // JS file name.
var scriptCustomizePreviewSRC   = './assets/js/'+ scriptCustomizePreviewFile +'.js'; // The JS file src.

var scriptRangeControlFile  	= 'wp-avatar-logo-range-control'; // JS file name.
var scriptRangeControlSRC   	= './assets/js/'+ scriptRangeControlFile +'.js'; // The JS file src.

var scriptDestination 		= './assets/js/'; // Path to place the compiled JS custom scripts file.
var scriptWatchFiles  		= './assets/js/*.js'; // Path to all *.scss files inside css folder and inside them.

var projectPHPWatchFiles    	= ['./**/*.php', '!_dist', '!_dist/**', '!_dist/**/*.php', '!_demo', '!_demo/**','!_demo/**/*.php'];

// Translations.
var text_domain             = '@@textdomain';
var destFile                = slug+'.pot';
var packageName             = project;
var bugReport               = pkg.author_uri;
var lastTranslator          = pkg.author;
var team                    = pkg.author_shop;
var translatePath           = './languages';
var translatableFiles       = ['./**/*.php'];

/**
 * Browsers you care about for autoprefixing. https://github.com/ai/browserslist
 */
const AUTOPREFIXER_BROWSERS = [
    'last 2 version',
    '> 1%',
    'ie >= 9',
    'ie_mob >= 10',
    'ff >= 30',
    'chrome >= 34',
    'safari >= 7',
    'opera >= 23',
    'ios >= 7',
    'android >= 4',
    'bb >= 10'
];

/**
 * Load Plugins.
 */
var gulp         = require('gulp');
var sass         = require('gulp-sass');
var minifycss    = require('gulp-clean-css');
var autoprefixer = require('gulp-autoprefixer');
var rename       = require('gulp-rename');
var lineec       = require('gulp-line-ending-corrector');
var filter       = require('gulp-filter');
var sourcemaps   = require('gulp-sourcemaps');
var browserSync  = require('browser-sync').create();
var reload       = browserSync.reload;
var cache        = require('gulp-cache');
var wpPot        = require('gulp-wp-pot');

/**
 * Clean gulp cache
 */
gulp.task('clear', function () {
   cache.clearAll();
});

gulp.task( 'browser_sync', function() {
	browserSync.init( {

	// Project URL.
	proxy: projectURL,

	// `true` Automatically open the browser with BrowserSync live server.
	// `false` Stop the browser from automatically opening.
	open: true,

	// Inject CSS changes.
	injectChanges: true,

	});
});

gulp.task('styles_frontend', function () {
	gulp.src( styleFrontendSRC )

	.pipe( sass( {
		errLogToConsole: true,
		outputStyle: 'expanded',
		precision: 10
	} ) )

	.on( 'error', console.error.bind( console ) )

	.pipe( autoprefixer( AUTOPREFIXER_BROWSERS ) )

	.pipe( csscomb() )

	.pipe( gulp.dest( styleDestination ) )

	.pipe( browserSync.stream() ) 

	.pipe( rename( { suffix: '.min' } ) )

	.pipe( minifycss( {
		maxLineLen: 10
	}))

	.pipe( gulp.dest( styleDestination ) )

	.pipe( browserSync.stream() )
});

gulp.task('styles_customizer_range', function () {
	gulp.src( styleRangeControlSRC )

	.pipe( sass( {
		errLogToConsole: true,
		outputStyle: 'expanded',
		precision: 10
	} ) )

	.on( 'error', console.error.bind( console ) )

	.pipe( autoprefixer( AUTOPREFIXER_BROWSERS ) )

	.pipe( csscomb() )

	.pipe( gulp.dest( styleDestination ) )

	.pipe( browserSync.stream() ) 

	.pipe( rename( { suffix: '.min' } ) )

	.pipe( minifycss( {
		maxLineLen: 10
	}))

	.pipe( gulp.dest( styleDestination ) )

	.pipe( browserSync.stream() )
});

gulp.task( 'scripts', function() {
	// wp-avatar-logo-customize-preview.js
	gulp.src( scriptCustomizePreviewSRC )
	.pipe( rename( {
		basename: scriptCustomizePreviewFile,
		suffix: '.min'
	}))
	.pipe( uglify() )
	.pipe( lineec() )
	.pipe( gulp.dest( scriptDestination ) )

	// wp-avatar-logo-range-control.js
	gulp.src( scriptRangeControlSRC )
	.pipe( rename( {
		basename: scriptRangeControlFile,
		suffix: '.min'
	}))
	.pipe( uglify() )
	.pipe( lineec() )
	.pipe( gulp.dest( scriptDestination ) )
});

/**
 * Build Tasks
 */

gulp.task( 'build-translate', function () {

	gulp.src( translatableFiles )

	.pipe( sort() )
	.pipe( wpPot( {
		domain        : text_domain,
		destFile      : destFile,
		package       : project,
		bugReport     : bugReport,
		lastTranslator: lastTranslator,
		team          : team
	} ))
	.pipe( gulp.dest( translatePath ) )

});

gulp.task( 'build-clean', function () {
	return gulp.src( ['./dist/*'] , { read: false } )
	.pipe(cleaner());
});

gulp.task( 'build-copy', function() {
    return gulp.src( buildFiles )
    .pipe( copy( buildDestination ) );
});

gulp.task('build-variables', function () {
	return gulp.src( distributionFiles )
	.pipe( replace( {
		patterns: [
		{
			match: 'pkg.version',
			replacement: version
		},
		{
			match: 'pkg.license',
			replacement: license
		},
		{
			match: 'pkg.author',
			replacement: author
		},
		{
			match: 'pkg.plugin_uri',
			replacement: plugin_uri
		},
		{
			match: 'pkg.copyright',
			replacement: copyright
		},
		{
			match: 'textdomain',
			replacement: pkg.textdomain
		},
		]
	}))
	.pipe( gulp.dest( buildDestination ) );
});

gulp.task( 'build-zip' , function() {
    return gulp.src( buildDestination+'/**' )
    .pipe( zip( 'wp-avatar-logo.zip' ) )
    .pipe( gulp.dest( './dist/' ) );
});

gulp.task( 'build-clean-after-zip', function () {
	return gulp.src( [ buildDestination, '!/dist/' + slug + '.zip'] , { read: false } )
	.pipe(cleaner());
});

gulp.task( 'build-notification', function () {
	return gulp.src( '' )
	.pipe( notify( { message: 'Your build of ' + packageName + ' is complete.', onLast: true } ) );
});

/**
 * Commands
 */

gulp.task( 'default', ['clear', 'styles_frontend', 'styles_customizer_range', 'scripts', 'browser_sync' ], function () {
	gulp.watch( projectPHPWatchFiles, reload );
	gulp.watch( styleWatchFiles, [ 'styles_frontend' ] );
	gulp.watch( styleWatchFiles, [ 'styles_customizer_range' ] );
	gulp.watch( scriptWatchFiles, [ 'scripts' ] );
});

gulp.task('build', function(callback) {
	runSequence( 'clear', 'build-clean', ['styles', 'styles_frontend', 'styles_customizer_range', 'scripts', 'build-translate'], 'build-clean', 'build-copy', 'build-variables', 'build-zip', 'build-clean-after-zip', 'build-notification', callback);
});
