timestamps {
    properties([pipelineTriggers([pollSCM('* * * * *')])])
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
            echo "run the docker build step"
            docker build -t cake_articles:latest_glaven .
            """
            // Shell build step
            sh """ 
            # Launch the container
            docker run -d -p 80:80 cake_articles:latest_glaven
            #curl --verbose http://builds.mini-super.com/index.php
            """ 
    	}
    }
}
