timestamps {
    node () {
    	stage ('Checkout') {
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
    	stage ('Build') {
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
            # Tag and push the image to the aws ecr repository
            docker tag cake_articles:"${BUILD_TAG}" 104352192622.dkr.ecr.us-west-2.amazonaws.com/cake_articles:"${BUILD_TAG}"
            docker push 104352192622.dkr.ecr.us-west-2.amazonaws.com/cake_articles:"${BUILD_TAG}"
            """
            sh """
            # Tear down the running container(s) and remove the docker images
            docker stop "${docker ps -l -q}";
            docker rm "${docker ps -l -q}";
            """
    	}
    }
}
