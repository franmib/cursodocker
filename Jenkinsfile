pipeline {
    environment {
        dockerImageFile1 = "franmib/curso:devops"
        dockerImage1 = ""
    }

    agent any
    stages {
        stage('Revisar código'){
            steps {
                git credentialsId: 'gitFranCursoDevops' , url: 'https://github.com/franmib/cursodocker.git', branch: 'main'
            }
        }
        stage('Construir imagen de aplicación') {
            steps {
                dir('app') {
                    script {
                        dockerImage1 = docker.build dockerImageFile1 
                    }
                }
            }
        }
    }
}