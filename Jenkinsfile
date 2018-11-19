pipeline {
   agent any
   environment {
      PATH = "$PATH:/usr/bin"
   }
       stages {
          stage ('Checkout') {
             steps {
               checkout([$class: 'GitSCM', 
     	            branches: [[name: '*/develop']], 
     	            doGenerateSubmoduleConfigurations: false, 
     	            extensions: [], 
     	            submoduleCfg: [], 
     	            userRemoteConfigs: [
     	                [credentialsId: 'edf6ddc3-92f1-496c-b829-b490b2743a51', 
     	                url: 'https://github.com/jasonwlcx/cake_articles/']]])
             }
          } // end of Checkout Stage
    	    stage ('Build') {
              steps {
     	            script { 
                      def receiver = docker.build("cake_articles:${BUILD_TAG}")
                  }
                  sh """ 
                  echo "Built the docker Image"
                  #docker build -t cake_articles:"${BUILD_TAG}" .
                  """
              }
          } // end of Build Stage
          stage ('Test') {
              steps {
                  script {
                     def image = docker.image.id("cake_articles:${BUILD_TAG}")
                     def container = image.withRun("--rm -p 80:80") {
                        "curl --verbose http://builds.mini-super.com/index.php"
                        "./vendor/bin/phpunit"
                     }
                  }
              }
          } // end of Test Stage
          stage ('Archive') {
              steps {
                  sh """
                  # Tag and push the image to the aws ecr repository
                  docker tag cake_articles:"${BUILD_TAG}" 104352192622.dkr.ecr.us-west-2.amazonaws.com/cake_articles:"${BUILD_TAG}"
                  docker push 104352192622.dkr.ecr.us-west-2.amazonaws.com/cake_articles:"${BUILD_TAG}"
                  """
              }
    	    }
       } // end stages
} // end pipeline
post {
    always {
        script { 
            container.stop()
        }
    }
} // end post
