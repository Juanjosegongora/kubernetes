#! /bin/bash

# ACTUALIZAR LA MAQUINA
apt update

#INSTALAR DOCKER
apt install docker.io -y

# INSTALACION DE COMPONENTES DE KUBERNETES
curl -s https://packages.cloud.google.com/apt/doc/apt-key.gpg | sudo apt-key add -
echo "deb https://apt.kubernetes.io/ kubernetes-xenial main" | sudo tee -a /etc/apt/sources.list.d/kubernetes.list
apt-get update
apt install kubelet kubeadm kubectl -y

# INSTALACION DE MINIKUBE.
# curl -LO https://storage.googleapis.com/minikube/releases/latest/minikube-linux-amd64
# install minikube-linux-amd64 /usr/local/bin/minikube

# HABILITAR EL SERVICIO DE DOCKER
systemctl enable docker.service

# INSTALACION DE KUBERNETES.
sudo kubeadm init

# CONFIGURACION.
mkdir -p $HOME/.kube
cp -i /etc/kubernetes/admin.conf $HOME/.kube/config
chown $(id -u):$(id -g) $HOME/.kube/config
