'use strict';

// Load plugins
var autoprefixer = require('autoprefixer');
var browsersync = require('browser-sync').create();
var cssnano = require('gulp-cssnano');
var del = require('del');
var gulp = require('gulp');
var concat = require('gulp-concat');
var plumber = require('gulp-plumber');
var minifyjs = require('gulp-uglify');
var rename = require('gulp-rename');
var sass = require('gulp-sass');

// BrowserSync
function browserSync(done)
{
  browsersync.init({
    open: false,
    port: 3000,
    server: {
      baseDir: './'
    }
  });
  done();
}
// html
function html()
{
  return gulp
  .src([
    './*.html',
    ])
  .pipe(browsersync.stream());
}
// clean
function clean()
{
  return del(['./assets/dist/']);
}

//css 
function css(){
    return gulp
    .src([
         './node_modules/datatables/media/css/jquery.dataTables.min.css',
        './assets/src/scss/main.scss',
    ])
    .pipe(sass({outputStyle:"expanded"}))
    .pipe(plumber())
    .pipe(concat('zoho-gf-integration-admin.css'))
    .pipe(gulp.dest('./admin/css/'))
    .pipe(rename({suffix:'.min'}))
    .pipe(cssnano())
    .pipe(gulp.dest('./admin/css/'))
    .pipe(browsersync.stream())
}

//script
function scripts(){
    return gulp
    .src([
        './node_modules/jquery/dist/jquery.js',
        './node_modules/datatables/media/js/jquery.dataTables.min.js',
       './node_modules/datatables.net-responsive/js/dataTables.responsive.min.js',
        './assets/src/js/**/*',
    ])
    .pipe(plumber())
    .pipe(concat('zoho-gf-integration-admin.js'))
    .pipe(gulp.dest('./admin/js/'))
    .pipe(minifyjs())
    .pipe(rename('zoho-gf-integration-admin.min.js'))
    .pipe(gulp.dest('./admin/js/'))
    .pipe(browsersync.stream())

}

// fonts
function fonts()
{
  return (
    gulp
    .src('./assets/src/fonts/**/*')
    .pipe(plumber())
    .pipe(gulp.dest('./admin/fonts/'))
    .pipe(browsersync.stream())
    );
}

// watch changes
function watchFiles()
{
  gulp.watch('./assets/src/scss/**/*', css);
  gulp.watch('./assets/src/js/**/*', scripts);
  gulp.watch('./assets/src/font/**/*', fonts);
  gulp.watch('./*.html', html);
}

const start = gulp.series(clean, css, scripts, html, fonts);
const watch = gulp.parallel(watchFiles, browserSync);

// export tasks
exports.css = css;
exports.scripts = scripts;
exports.clean = clean;
exports.fonts = fonts;
exports.watch = watch;
exports.default = gulp.series(start, watch);
