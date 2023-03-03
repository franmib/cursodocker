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
                            sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl apply -f fbo_deployment.yaml -n fbo2 --kubeconfig=/home/digesetuser/.kube/config'           
                            sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl rollout restart deployment fboapp -n fbo2 --kubeconfig=/home/digesetuser/.kube/config'
                            //sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl rollout status deployment fboapp -n fbo2 --kubeconfig=/home/digesetuser/.kube/config'          
                        }catch(error)       
                        {}
                    }

                    sh 'cd mysql8 && scp -r -o StrictHostKeyChecking=no fbo_deployment.yaml digesetuser@148.213.1.131:/home/digesetuser/'      
                    script{        
                        try{           
                            sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl apply -f fbo_deployment.yaml -n fbo2 --kubeconfig=/home/digesetuser/.kube/config'           
                            sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl rollout restart deployment fbomysql-deployment -n fbo2 --kubeconfig=/home/digesetuser/.kube/config'
                            //sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl rollout status deployment fbomysql-deployment -n fbo2 --kubeconfig=/home/digesetuser/.kube/config'          
                        }catch(error)       
                        {}
                    }

                    sh 'cd phpmyadmin && scp -r -o StrictHostKeyChecking=no fbo_deployment.yaml digesetuser@148.213.1.131:/home/digesetuser/'      
                    script{        
                        try{           
                            sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl apply -f fbo_deployment.yaml -n fbo2 --kubeconfig=/home/digesetuser/.kube/config'           
                            sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl rollout restart deployment fbophpmyadmin-deployment -n fbo2 --kubeconfig=/home/digesetuser/.kube/config'
                            //sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl rollout status deployment fbophpmyadmin-deployment -n fbo2 --kubeconfig=/home/digesetuser/.kube/config'          
                        }catch(error)       
                        {}
                    }

                    sh 'cd apirest && scp -r -o StrictHostKeyChecking=no fbo_deployment.yaml digesetuser@148.213.1.131:/home/digesetuser/'      
                    script{        
                        try{           
                            sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl apply -f fbo_deployment.yaml -n fbo2 --kubeconfig=/home/digesetuser/.kube/config'           
                            sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl rollout restart deployment fboapi -n fbo2 --kubeconfig=/home/digesetuser/.kube/config'
                            //sh 'ssh digesetuser@148.213.1.131 microk8s.kubectl rollout status deployment fbophpmyadmin-deployment -n fbo2 --kubeconfig=/home/digesetuser/.kube/config'          
                        }catch(error)       
                        {}
                    }
                }
            }                
        }
    }
    post
    {
        success{
            slackSend channel: 'fran', color: 'good', failOnError: true, message: "${custom_msg()}", teamDomain: 'universidadde-bea3869', tokenCredentialId: 'slackpass' 
        }
    }
}

def custom_msg()
{
    def JENKINS_URL= "jarvis.ucol.mx:8080"
    def JOB_NAME = env.JOB_NAME
    def BUILD_ID= env.BUILD_ID
    def JENKINS_LOG= " DEPLOY LOG: Job [${env.JOB_NAME}] Logs path: ${JENKINS_URL}/job/${JOB_NAME}/${BUILD_ID}/consoleText"
    return JENKINS_LOG
}
