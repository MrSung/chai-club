var gulp = require('gulp');
var sass = require('gulp-sass');
var rename = require('gulp-rename');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var sourcemaps = require('gulp-sourcemaps');
var postcss = require('gulp-postcss');
var cssnext = require('postcss-cssnext');

var paths = {
  'scss': 'src/scss/',
  'css': 'dist/css/',
  'jsSrc': [
    //'src/js/aos.js',
    'src/js/jquery.validate.min.js',
    'src/js/main.js'
  ],
  'jsDist': 'dist/js/'
};

gulp.task('sass', function () {
  var processors = [
      cssnext()
  ];
  return gulp.src(paths.scss + '**/*.scss')
    .pipe(sourcemaps.init())
    .pipe(sass({
      outputStyle: 'compact',
      includePaths: require('node-reset-scss').includePath
    }))
    .on('error', function (err) {
      console.log(err.message);
    })
    .pipe(postcss(processors))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.css));
});

gulp.task('sass-watch', function () {
  gulp.watch([paths.scss + '**/*.scss'], ['sass']);
});

gulp.task('js-concat', function () {
  gulp.src(paths.jsSrc)
    .pipe(concat('scripts.js'))
    .pipe(gulp.dest(paths.jsDist));
});

gulp.task('js-uglify', function () {
  gulp.src(paths.jsDist + 'scripts.js')
    .pipe(uglify())
    .pipe(rename({
      extname: '.min.js'
    }))
    .pipe(gulp.dest(paths.jsDist));
});

gulp.task('js', ['js-concat']); // Add 'js-uglify' if necessary

gulp.task('js-watch', function () {
  gulp.watch([paths.jsSrc], ['js']);
});

gulp.task('default', ['sass', 'sass-watch', 'js', 'js-watch']);
