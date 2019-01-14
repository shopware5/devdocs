module.exports = (grunt) => {
    grunt.initConfig({
        less: {
            development: {
                options: {
                    compress: true,
                    sourceMapFileInline: true,
                    rootpath: 'assets/'
                },
                files: {
                    'output_dev/assets/css/basic.css': 'source/assets/css/basic.less'
                }
            }
        },

        watch: {
            styles: {
                files: ['source/assets/css/*/**.less', 'source/assets/css/*.less'],
                tasks: ['less:development'],
                options: {
                    livereload: true
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-less');

    grunt.registerTask('default', [ 'less:development' ]);
};
