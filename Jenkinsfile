pipeline {
   agent any
        environment {
            PATH = "$PATH:/usr/bin"
        }
        stages {
     	            checkout([$class: 'GitSCM', 
     	            branches: [[name: '*/develop']], 
     	            doGenerateSubmoduleConfigurations: false, 
     	            extensions: [], 
     	            submoduleCfg: [], 
     	            userRemoteConfigs: [
     	                [credentialsId: 'edf6ddc3-92f1-496c-b829-b490b2743a51', 
     	                url: 'https://github.com/jasonwlcx/cake_articles/']]
     	            ])
    	    stage ('Build') {
                steps {
     	            script { 
                        def receiver = docker.build("cake_articles:${BUILD_TAG}")
                        def receiver_container = receiver.withRun("--rm -p 80:80") {
                        sh """
                        ./var/www/html/vendor/bin/phpunit
                        curl --verbose http://builds.mini-super.com/index.php
                        """
                  }
                  sh """ 
                  echo "Built the docker Image"
                  #docker build -t cake_articles:"${BUILD_TAG}" .
                  """
                }
            }
            stage ('Archive') {
                steps {
                    sh """
                    # Tag and push the image to the aws ecr repository
                    docker tag cake_articles:"${BUILD_TAG}" 104352192622.dkr.ecr.us-west-2.amazonaws.com/cake_articles:"${BUILD_TAG}"
                    docker push 104352192622.dkr.ecr.us-west-2.amazonaws.com/cake_articles:"${BUILD_TAG}"
                    """
                }
    	    }
        }
}
post {
    always {
        script { 
            receiver_container.stop()
        }
    }
}
