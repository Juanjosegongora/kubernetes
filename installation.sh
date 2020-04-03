#! /bin/bash

# ACTUALIZAR LA MAQUINA
apt update

#INSTALAR DOCKER
apt install docker.io -y

# HABILITAR EL SERVICIO DE DOCKER
systemctl enable docker.service

# INSTALACION DE COMPONENTES DE KUBERNETES
curl -s https://packages.cloud.google.com/apt/doc/apt-key.gpg | sudo apt-key add -
echo "deb https://apt.kubernetes.io/ kubernetes-xenial main" | sudo tee -a /etc/apt/sources.list.d/kubernetes.list
apt-get update
apt install kubelet kubeadm kubectl -y

# INSTALACION DE MINIKUBE.
read -n1 -p "Quieres instala minikube? (y/n) " RESP_MINIKUBE
if [ $RESP_MINIKUBE = "y" ]; then
    curl -LO https://storage.googleapis.com/minikube/releases/latest/minikube-linux-amd64
    install minikube-linux-amd64 /usr/local/bin/minikube
fi

# INSTALACION DE KUBERNETES.
sudo kubeadm init

# CONFIGURACION.
mkdir -p $HOME/.kube
cp -i /etc/kubernetes/admin.conf $HOME/.kube/config
chown $(id -u):$(id -g) $HOME/.kube/config
echo

# K3S
read -n1 -p "Quires instalar K3S? (y/n) " RESP_K3S
if [ $RESP_K3S -eq "y" ]; then
    cd /usr/local/bin/
    wget https://github.com/rancher/k3s/releases/download/v0.2.0/k3s
    chmod +x k3s
    read -p "Que tipo de nodo es? (master/worker) " TIPO_NODO
    if [ $TIPO_NODO = "master" ]; then
        k3s server &
        TOKEN=$(cat /var/lib/rancher/k3s/server/node-token)
        echo "Tu token es la siguiente"
        echo $TOKEN
    elif [ $TIPO_NODO = "worker" ]; then
        read -p "Dame la IP del equipo Master" IP_MASTER
        read -p "Dame El token" TOKEN
        if [ $IP_MASTER > /dev/null ] || [ $TOKEN > /dev/null ]; then
            k3s agent --server https://$IP_MASTER:6443 --token $TOKEN
        else
            echo "Alguno de los valores de token o IP no me los has introducido"
            break
        fi
    else
        echo "No me has introducido ni 'master' ni 'worker'"
        break
    fi
fi