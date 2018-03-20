var vhost = "http://localhost:8081/",
    theme = "Divi-cspace";

var gulp = require("gulp"),
    postcss = require("gulp-postcss"),
    autoprefixer = require("autoprefixer"),
    sass = require("gulp-sass"),
    cssnano = require("gulp-cssnano"),
    rename = require("gulp-rename"),
    uglify = require("gulp-uglify"),
    concat = require("gulp-concat"),
    webserver = require("gulp-webserver"),
    browserSync = require("browser-sync");

gulp.task("watch", function() {
    gulp.watch("./scss/**/*.scss", ["main-css"]);
    gulp.watch("./js/*.js", ["main-js"]);
    gulp
        .watch(global + "/themes/" + theme + "/**/*.php")
        .on("change", browserSync.reload);
    ///Users/sidelsky/Dropbox/www/cspace/themes/Divi-cspace
});

gulp.task("main-css", function() {
    return gulp
        .src(["./scss/main.scss"])
        .pipe(sass())
        .pipe(concat("../../style.css"))
        .pipe(gulp.dest("./"))
        .pipe(
            browserSync.reload({
                stream: true
            })
        )
        .pipe(
            rename({
                suffix: ".min"
            })
        )
        .pipe(cssnano())
        .pipe(gulp.dest("./"));
});

//Browser sync
gulp.task("browserServe", ["watch"], function(done) {
    browserSync(
        {
            open: true,
            port: 3000,
            notify: false,
            proxy: {
                target: vhost,
                middleware: function(req, res, next) {
                    res.setHeader("Access-Control-Allow-Origin", "*");
                    res.setHeader(
                        "Access-Control-Allow-Headers",
                        "Content-Type, X-Requested-With"
                    );
                    res.setHeader(
                        "Access-Control-Allow-Methods",
                        "GET, POST",
                        "OPTIONS"
                    );
                    next();
                }
            }
        },
        done
    );
});

gulp.task("main-js", function() {
    return gulp
        .src(["./js/main.js"])
        .pipe(uglify())
        .pipe(concat("../../main.min.js"))
        .pipe(gulp.dest("./"));
});

gulp.task("autoprefixer", function() {
    return gulp
        .src("./css/*.css")
        .pipe(postcss([autoprefixer()]))
        .pipe(gulp.dest("./"));
});

// gulp.task("webserver", function() {
//     gulp.src("app").pipe(
//         webserver({
//             livereload: true,
//             directoryListing: true,
//             open: true
//         })
//     );
// });

gulp.task("default", ["main-css", "main-js", "autoprefixer", "browserServe"]);
