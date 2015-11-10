/* jshint ignore:start */

var gulp = require('gulp'),
    sass = require('gulp-ruby-sass'),
    minifycss = require('gulp-minify-css'),
    jshint = require('gulp-jshint'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    notify = require('gulp-notify'),
    cache = require('gulp-cache'),
    del = require('del'),
    gutil = require('gulp-util'),
    bower = require('gulp-bower');

var config = {
    bowerDir: './bower_components',
    sassDir: './scss',
    publicDir: '../public'
};

// Download bootstrap, jquery, datatables and summernote

gulp.task('bower', function () {
    return bower()
        .pipe(gulp.dest(config.bowerDir))
});

// Move the fonts to the public dir

gulp.task('fonts', function () {
    gulp.src([
        config.bowerDir + '/bootstrap-sass-official/assets/fonts/**/*.{ttf,woff,woff2,eof,eot,svg}',
        config.bowerDir + '/font-awesome/fonts/*.{ttf,woff,woff2,eof,eot,svg}',
    ])
        .pipe(gulp.dest(config.publicDir + '/fonts'));
});

// Compile sass

gulp.task('sass', function () {
    // Cannot include summernote scss here because the current version is not up to date.
    // That's the reason why there is another task to concat summernote.css, which is right.
    return sass(config.sassDir + '/main.scss', {
        style: 'compressed',
        loadPath: [
            config.sassDir,
            config.bowerDir + '/bootstrap-sass-official/assets/stylesheets',
            config.bowerDir + '/font-awesome/scss'
        ]
    }).on("error", notify.onError(function (error) {
        return "Error: " + error.message;
    }))
        .pipe(gulp.dest(config.publicDir + '/css'));
});

// Build the stylesheet

gulp.task('style', ['sass'], function () {
    gulp.src([
        config.publicDir + '/css/main.css',
        config.bowerDir + '/datatables/media/css/dataTables.bootstrap.css',
        config.bowerDir + '/summernote/dist/summernote.css'
    ])
        .pipe(concat('style.css'))
        .pipe(gulp.dest(config.publicDir + '/css'))
        .pipe(rename({suffix: '.min'}))
        .pipe(minifycss())
        .pipe(gulp.dest(config.publicDir + '/css'));

    del(config.publicDir + '/css/main.css', {force: true});
});

// Build the javascript

gulp.task('javascript', function () {
    gulp.src([
        config.bowerDir + '/jquery/dist/jquery.js',
        config.bowerDir + '/bootstrap-sass-official/assets/javascripts/bootstrap.js',
        config.bowerDir + '/datatables/media/js/jquery.dataTables.js',
        config.bowerDir + '/datatables/media/js/dataTables.bootstrap.js',
        config.bowerDir + '/summernote/dist/summernote.js'
    ])
        .pipe(concat('main.js'))
        .pipe(gulp.dest(config.publicDir + '/js'))
        .pipe(uglify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(config.publicDir + '/js'));
});

// Remove all the assets in public

gulp.task('clean', function (cb) {
    del([config.publicDir + '/css', config.publicDir + '/js', config.publicDir + '/fonts'], {force: true}, cb)
});

// Default task

gulp.task('default', ['bower', 'clean'], function () {
    gulp.start('fonts', 'style', 'javascript');
});

// Watch task

gulp.task('watch', function () {
    gulp.watch(config.sassDir + '/**/*.scss', ['style']);
});