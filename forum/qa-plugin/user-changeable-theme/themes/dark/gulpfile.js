const gulp = require('gulp')
const nano = require('gulp-cssnano')
const sass = require('gulp-sass')
const live = require('gulp-livereload')
const plumber = require('gulp-plumber')
const prefixer = require('gulp-autoprefixer')

gulp.task('sass', () => {
	gulp.src('src/main.scss')
		.pipe(plumber())
		.pipe(sass())
		.pipe(prefixer())
		.pipe(nano())
		.pipe(gulp.dest('build/'))
		.pipe(live())

})

gulp.task('watch', () => {
	live.listen(3001)
	gulp.watch('src/**', ['sass'])
})

gulp.task('default', ['sass'])
