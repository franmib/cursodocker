pipeline {
    environment {
        dockerImageFile1 = "franmib/curso:devops"
        dockerImage1 = ""

        dockerImageFile2 = "franmib/phpmyadmin:devops"
        dockerImage2 = ""

        dockerImageFile3 = "franmib/mysql:devops"
        dockerImage3 = ""
    }

    agent any
    stages {
        stage('Revisar código'){
            steps {
                git credentialsId: 'gitFranCursoDevops' , url: 'https://github.com/franmib/cursodocker.git', branch: 'main'
            }
        }
        stage('Construir imagenes') {
            steps {
                dir('app') {
                    script {
                        dockerImage1 = docker.build dockerImageFile1 
                    }
                }

                dir('phpmyadmin') {
                    script {
                        dockerImage2 = docker.build dockerImageFile2 
                    }
                }

                dir('mysql8') {
                    script {
                        dockerImage3 = docker.build dockerImageFile3
                    }
                }
            }

        }
        stage('Subir imagenes') {
            environment {
                registryCredential = 'dockerFranDevOps'
            }
            steps {
                dir('app') {
                    script {
                        docker.withRegistry('https://registry.hub.docker.com', registryCredential) {

                            dockerImage1.push("devops") //ahí va el tag
                        }
                    }
                }

                dir('phpmyadmin') {
                    script {
                        docker.withRegistry('https://registry.hub.docker.com', registryCredential) {

                            dockerImage2.push("devops") //ahí va el tag
                        }
                    }
                }

                dir('mysql8') {
                    script {
                        docker.withRegistry('https://registry.hub.docker.com', registryCredential) {

                            dockerImage3.push("devops") //ahí va el tag
                        }
                    }
                }
            }
        }
        stage('Correr POD'){
            steps {
                sshagent(['rodriguezssh']) {
                    sh 'cd app && scp -r -o StrictHostKeyChecking=no fbo_deployment.yaml digesetuser@148.213.1.131:/home/digesetuser/'      
                    script{        
                        try{           
                            sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl apply -f fbo_deployment.yaml --kubeconfig=/home/digesetuser/.kube/config'           
                            sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl rollout restart fboapp --kubeconfig=/home/digesetuser/.kube/config'
                            sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl rollout status  fboapp --kubeconfig=/home/digesetuser/.kube/config'          
                        }catch(error)       
                        {}
                    }
                }
            }                
        }
    }
}