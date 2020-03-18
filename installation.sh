#! /bin/bash

# ACTUALIZAR LA MAQUINA
apt update

#INSTALAR DOCKER
apt install docker.io -y

# INSTALACION DE COMPONENTES DE CUBERNETES
curl -s https://packages.cloud.google.com/apt/doc/apt-key.gpg | sudo apt-key add -
echo "deb https://apt.kubernetes.io/ kubernetes-xenial main" | sudo tee -a /etc/apt/sources.list.d/kubernetes.list
apt-get update
apt install kubelet kubeadm kubectl -y

# HABILITAR EL SERVICIO DE DOCKER
systemctl enable docker.service
