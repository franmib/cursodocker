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
               
            }
        }
        stage('Correr POD'){
            steps {
                sshagent(['rodriguezssh']) {
                    sh 'cd app && scp -r -o StrictHostKeyChecking=no fbo_deployment.yaml digesetuser@148.213.1.131:/home/digesetuser/'      
                    script{        
                        try{           
                            sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl apply -f fbo_deployment.yaml -n fbo --kubeconfig=/home/digesetuser/.kube/config'           
                            sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl rollout restart deployment fboapp -n fbo --kubeconfig=/home/digesetuser/.kube/config'
                            sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl rollout status deployment fboapp -n fbo --kubeconfig=/home/digesetuser/.kube/config'          
                        }catch(error)       
                        {}
                    }

                    sh 'cd mysql8 && scp -r -o StrictHostKeyChecking=no fbo_deployment.yaml digesetuser@148.213.1.131:/home/digesetuser/'      
                    script{        
                        try{           
                            sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl apply -f fbo_deployment.yaml -n fbo --kubeconfig=/home/digesetuser/.kube/config'           
                            sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl rollout restart deployment fbomysqlpod -n fbo --kubeconfig=/home/digesetuser/.kube/config'
                            sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl rollout status deployment fbomysqlpod -n fbo --kubeconfig=/home/digesetuser/.kube/config'          
                        }catch(error)       
                        {}
                    }

                    sh 'cd phpmyadmin && scp -r -o StrictHostKeyChecking=no fbo_deployment.yaml digesetuser@148.213.1.131:/home/digesetuser/'      
                    script{        
                        try{           
                            sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl apply -f fbo_deployment.yaml -n fbo --kubeconfig=/home/digesetuser/.kube/config'           
                            sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl rollout restart deployment fbophpmyadmin -n fbo --kubeconfig=/home/digesetuser/.kube/config'
                            sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl rollout status deployment fbophpmyadmin -n fbo --kubeconfig=/home/digesetuser/.kube/config'          
                        }catch(error)       
                        {}
                    }
                }
            }                
        }
    }
}