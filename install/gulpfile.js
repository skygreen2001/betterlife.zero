/* jshint node: true, strict: true */
'use strict';

/*=====================================
=        Default Configuration        =
=====================================*/

// Please use config.js to override these selectively:

var config = {
  dest: '../bin',
  server: {
    host: '0.0.0.0',
    port: '8000'
  },
  clean: {
    template_cache_dir: "../home/**/tmp/templates_c/**",
    mac_ignore_file: "../**/.DS_Store"
  }
};

if (require('fs').existsSync('../config.js')) {
  var configFn = require('../config');
  configFn(config);
}
/*-----  End of Configuration  ------*/


/*========================================
=            Requiring stuffs            =
========================================*/

var gulp           = require('gulp'),

    bower          = require('gulp-bower'),
    composer       = require('gulp-composer'),

    seq            = require('run-sequence'),
    connect        = require('gulp-connect'),
    less           = require('gulp-less'),
    uglify         = require('gulp-uglify'),
    sourcemaps     = require('gulp-sourcemaps'),
    cssmin         = require('gulp-cssmin'),
    order          = require('gulp-order'),
    concat         = require('gulp-concat'),
    ignore         = require('gulp-ignore'),
    rimraf         = require('gulp-rimraf'),
    mobilizer      = require('gulp-mobilizer'),
    replace        = require('gulp-replace'),
    streamqueue    = require('streamqueue'),
    rename         = require('gulp-rename'),
    path           = require('path');


/*================================================
=            Report Errors to Console            =
================================================*/

gulp.on('error', function(e) {
  throw(e);
});


/*=========================================
=         Setup Composer Library          =
=========================================*/
// 可在install下的composer.json里配置安装的路径
// {
//     "config": {
//         "vendor-dir": "../vendor"
//     }
// }
gulp.task('composer', function (cb) {
  return composer({
    'self-install': false,
    'no-ansi'     : true,
    'working-dir' : '../install'
  });
});


/*=========================================
=          Setup Bower Library            =
=========================================*/

gulp.task('bower', function (cb) {
  return bower({
    directory: '../install/bower_components',
    cwd: '../install'
  });
});


/*=========================================
=            Clean dest folder            =
=========================================*/
gulp.task('clean', function (cb) {
  return gulp.src([
        config.clean.template_cache_dir,
        config.clean.mac_ignore_file,
        config.dest
      ], { read: false })
     .pipe(rimraf({ force: true }));
});


/*==========================================
=            Start a web server            =
==========================================*/

gulp.task('connect', function() {
  if (typeof config.server === 'object') {
    connect.server({
      root: config.dest,
      host: config.server.host,
      port: config.server.port,
      livereload: true
    });
  } else {
    throw new Error('Connect is not configured');
  }
});


/*==============================================================
=            Setup live reloading on source changes            =
==============================================================*/

gulp.task('livereload', function () {
  gulp.src(
      path.join(config.dest, '*.html'),
      path.join(config.dest, '*.php'),
      path.join(config.dest, '*.tpl')
    )
    .pipe(connect.reload());
});


/*===================================================================
=            Watch for source changes and rebuild/reload            =
===================================================================*/

gulp.task('watch', function () {
  if (typeof config.server === 'object') {
    gulp.watch([config.dest + '/**/*'], ['livereload']);
  }
  gulp.watch(['./core/**/*'], ['php']);
  gulp.watch(['./home/**/*'], ['php','tpl']);
});


/*======================================
=            Build Sequence            =
======================================*/

gulp.task('build', function(done) {
  var tasks = [''];
  seq('clean', tasks, done);
});


/*======================================
=            Install Sequence          =
======================================*/

gulp.task('install', function(done) {
  var tasks = ['bower'];
  seq('composer', tasks, done);
});


/*====================================
=            Default Task            =
====================================*/
gulp.task('default', function(done){
  var tasks = [];
  tasks.push('install');
  seq('clean', done);//tasks, 
});
