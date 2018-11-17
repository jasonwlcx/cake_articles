timestamps {
    node () {

    	stage ('a_freestyle_cake - Checkout') {
     	 checkout([$class: 'GitSCM', 
     	    branches: [[name: '*/develop']], 
     	    doGenerateSubmoduleConfigurations: false, 
     	    extensions: [], 
     	    submoduleCfg: [], 
     	    userRemoteConfigs: [
     	        [credentialsId: 'edf6ddc3-92f1-496c-b829-b490b2743a51', 
     	            url: 'https://github.com/jasonwlcx/cake_articles/']
     	        ]
     	 ]) 
    	}
    	stage ('a_freestyle_cake - Build') {
     	    // Shell build step
            sh """ 
            echo "Build the docker Image"
            docker build -t cake_articles:"${BUILD_TAG}" .
            """
            // Shell build step
            sh """ 
            # Launch the container
            docker run --rm -d -p 80:80 cake_articles:"${BUILD_TAG}"
            curl --verbose http://builds.mini-super.com/index.php
            """
            sh """
            # Authenticate, tag and push the image to the aws ecr repository
            #"${aws ecr get-login --no-include-email}"
            docker tag cake_articles:"${BUILD_TAG}" 104352192622.dkr.ecr.us-west-2.amazonaws.com/cake_articles:"${BUILD_TAG}"
            #docker push 104352192622.dkr.ecr.us-west-2.amazonaws.com/cake_articles:"${BUILD_TAG}"
            """
            sh """
            # Tear down the running container(s) and remove the docker images
            #docker stop "${docker ps -l -q}";
            #docker rm "${docker ps -l -q}";
            echo "success"
            """
    	}
        stage 'Docker push'
            docker.withRegistry('https://104352192622.dkr.ecr.us-west-2.amazonaws.com/cake_articles', 'ecr:us-west-2:cake_articles-ecr-credentials') {
            docker.image('cake_articles').push("${BUILD_TAG}")
        }
    }
}
