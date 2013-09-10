module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        copy: {
            main: {
                files: [{
                    expand: true,
                    cwd: 'bower/bootstrap/fonts',
                    src: ['*'],
                    dest: 'fonts/',
                    filter: 'isFile'
                }, {
                    expand: true,
                    cwd: 'bower/flags/flags/flags-iso/shiny/16',
                    src: ['*.png'],
                    dest: 'img/flags',
                    filter: 'isFile'
                }]
            }
        },
        less: {
            dev: {
                options: {
                    paths: ['css']
                },
                files: {
                    'css/etalab.css': ['less/etalab.less']
                }
            },
            prod: {
                options: {
                    paths: ['css'],
                    yuicompress: true
                },
                files: {
                    'css/etalab.min.css': ['less/etalab.less']
                }
            }
        },
        uglify: {
            options: {
                banner: '/*! <%= pkg.name %> v<%= pkg.version %> \n' + '* http://noirbizarre.github.com/bootstrap-pickers \n' + '* Copyright (c) <%= grunt.template.today("yyyy") %> Axel Haustant \n' + '* MIT License \n' + '*/'
            },
            build: {
                files: {
                    'js/etalab.min.js': ['bower/jquery/jquery.js', 'bower/bootstrap/dist/js/bootstrap.js', 'js/etalab.js'],
                    'js/etalab-legacy.min.js': ['bower/jquery-legacy/index.js', 'bower/bootstrap/dist/js/bootstrap.js', 'js/etalab.js'],
                    'js/modernizr.min.js': ['bower/modernizr/modernizr.js', 'bower/respond/respond.src.js']
                }
            }
        },
        jshint: {
            // define the files to lint
            files: ['gruntfile.js'],
            // configure JSHint (documented at http://www.jshint.com/docs/)
            options: {
                // more options here if you want to override JSHint defaults
                globals: {
                    jQuery: true,
                    console: true,
                    module: true
                }
            }

        },
        watch: {
            javascript: {
                files: ['js/etalab.js'],
                tasks: ['uglify']
            },
            style: {
                files: ['less/etalab.less'],
                tasks: ['less']
            },
        }
    });

    // Load libs
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-less');

    // Register the default tasks
    grunt.registerTask('default', ['copy', 'less', 'uglify']);

};
