module.exports = (grunt) => {
    grunt.initConfig({
        less: {
            development: {
                options: {
                    compress: true
                },
                files: {
                    'output_dev/assets/css/basic.css': 'source/assets/css/basic.less'
                }
            }
        },

        watch: {
            styles: {
                files: 'source/assets/css/*/**.less',
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
