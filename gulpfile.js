var gulp = require('gulp');
var $    = require('gulp-load-plugins')();
var php = require('gulp-connect-php');
var php = require('gulp-sass');
var browserSync = require('browser-sync').create();
//const image = require('gulp-image');
var minify = require('gulp-minify');

var sassPaths = [
  'scss/bootstrap',
  'node_modules/motion-ui/src'
];
gulp.task('serve', ['sass'], function() {

    browserSync.init({
       proxy: "localhost"
   });
});
gulp.task('compress', function() {
  gulp.src('scripts_uniminified/*.js')
    .pipe(minify({
        ext:{
            src:'-debug.js',
            min:'.js'
        },
        exclude: ['tasks'],
        ignoreFiles: ['ajax.js', 'status.js', '.combo.js', '-min.js']
    }))
    .pipe(gulp.dest('scripts'))
});

gulp.task('sass', function() {
  return gulp.src('scss/bootstrap/style.scss', { style: 'expanded' })
    .pipe($.sass({
      includePaths: sassPaths,
      outputStyle: 'compressed' // if css compressed **file size**
    })
      .on('error', $.sass.logError))
    .pipe($.autoprefixer({
      browsers: ['last 2 versions', 'ie >= 9']
    }))
    .pipe(gulp.dest('templates/2/css'))
	.pipe(browserSync.stream());
});
//gulp.task('image', function () {
  //gulp.src('./pic/**/**/*')
 //      .pipe(image({
  //     pngquant: false,
 //      optipng: false,
 //      zopflipng: false,
 //      jpegRecompress: false,
 //      mozjpeg: false,
 //      guetzli: false,
 //      gifsicle: false,
 //      svgo: false,
 //      concurrent: 10,
//       quiet: false // defaults to false
 //}))
//  .pipe(gulp.dest('./pic'));
//});

//not totally there yet will need to be more specific
//gulp.task('watch', function() {
//  browserSync.init({proxy: "https://u-232.servebeer.com:3000"});
 // gulp.watch(['scss/**/*.scss']).on('change', browserSync.reload);
 //gulp.watch("*.php").on('change', browserSync.reload);
//});



gulp.task('default', ['sass' ,'serve', 'compress'], function() {
  gulp.watch(['scss/**/*.scss'], ['sass']);
  gulp.watch("*.php").on('change', browserSync.reload);
});


