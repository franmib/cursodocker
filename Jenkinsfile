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
    }
}