/**
 * requirements for gulpfile to work
 * @param importer for gulp file
 */
var gulp = require("gulp");
var sass = require("gulp-dart-sass");
var cssnano = require("gulp-cssnano");
var sourcemaps = require("gulp-sourcemaps");
var rename = require("gulp-rename");

/**
 * path variables
 * @var path_includer
 */
var scss_files = "./Assets/Src/Styles/TableStyles/*.scss";
var scss_compile_dest = "./Assets/Public/Styles/";

/**
 * @function sass_compiler for scss file
 */
const sass_compiler = async () => {
    gulp.src(scss_files)
        .pipe(
            sourcemaps.init({
                loadMaps: true,
            })
        )
        .pipe(sass().on("error", sass.logError))
        .pipe(cssnano())
        .pipe(
            rename({
                suffix: ".min",
            })
        )
        .pipe(sourcemaps.write("./"))
        .pipe(gulp.dest(scss_compile_dest));
};
const watch = () => {
    gulp.watch("./Assets/Src/Styles/TableStyles/*.scss", sass_compiler);
};
exports.default = watch;
