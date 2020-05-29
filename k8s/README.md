Get Helm v3: https://github.com/helm/helm/releases

helm repo add stable https://kubernetes-charts.storage.googleapis.com
helm repo add bitnami https://charts.bitnami.com/bitnami
helm repo update

## MariaDB setup

```
helm install sshauth-mariadb bitnami/mariadb-galera -f sshauth-production.yaml -n sshauth-mariadb
```
