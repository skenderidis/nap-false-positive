# NAP - False Positive Management
> NAP-FPM make it easier for SecOps teams to make changes to the NGINX App Protect policies.


## How does it works
NAP-FPM UI provides visibility to SecOps teams, for the different violations that exist on a SupportID and also gives the option to modify the NAP policy  with few simple clicks.


In order for NAP-FPM to work it requires the following two components:
- Datasource where it can pull the NAP events from. Currently the support datasource is Elasticsearch. 
- GitLab to store the policies. NAP-FPM will interact with the GitLab API to pull the policy, make the necessary changes and then push the updated NAP policies back to Gitlab .

<img src="fpm.gif"/>

## Pre-requisites
In order for this tool to work 

To run this Dashboard you will need to deploy following open source solutions. 
- Logstash
- Elasticsearch 
- Grafana
- Docker
- Docker Compose
- Python 3.7+

