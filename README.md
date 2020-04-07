# KUBERNETES.
## JUAN JOSE GONGORA CONTRERAS

# QUE ES KUBERNETES.
Cubernetes en una plataforma de codido abierto para el despligue, escalado y gestion de aplicaciones contenedorizadas.

# CLUSTER DE KUBERNETES.
Esta compuesto por dos tipos de recursos.

- Master: Coordina todas las actividades del cluster como organizar apicaciones, mantener el estado de aplicaciones, escalado, despliegue de actualizaciones. Tambien recoge informacion de los nodos worker y los pods.

- Nodos: son workers que ejecutan las aplicaciones. Cada nodo contiene un agente llamado Kubelet que gestiona el nodo y mantiene la comunicacion con el master.

En el despliegue de una aplicacion en Kubernetes el master es el que inicia y organiza los contenedores para que se ejecuten en los nodos del cluster. La comunicacion entre ellos se hace mediante la `API de Kubernetes`.

# ARQUITECTURA DE KUBERNETES.
En la siguiente imagen podemos ver los componentes mas importantes que tienen un master y un nodo.

![](images/1.png)

- Plugins de red: Permiten la conexion entre pods de nodos difrerentes y la integracion de soluciones de red.
- Es una base de datos de clave-valor donde Kubernetes guarda todos los datos del cluster.
- API server: Componente del master que expone la API de Kubernetes
- Control manager: se encarga de comprobar si el estado deseado coincide con el de la realidad.
- Scheduler: Componente del master que obserba que pods se han creado nuevos y no tienen nodo adignado, y les selecciona el nodo donde pueden ejecutarse.
- Kubelet: Agente que se ejecuta en cada nodo worker del cluster y que se asegura que los nodos estan en ejecucion y sanos. Kubelet no gestiona los pods que no han sido creados por kubernetes.
- Kube-proxy: Mantiene las reglas del networking en los nodos para los pods que se ejecutan en él de acuerdo con las especificaciones de los manifiestos.
- cAdvisor: Recoge los datos de uso de los contenedores.
- control plane: Nivel de orquestacion de contenedores que expone la API para definir, desplegar y gestionar el ciclo de vida de los contenedores.
- Data plane: Nivel que proporciona los recursos, como CPU, memoria, red y almacenamiento, para que los pods se puedan ejecutar y conectar a la red.

# MINIKUBE.
Es una implementacion ligera de kubernetes que crea una maquina virtual localmente y despliega un cluster sencillo formado por un solo nodo.
 - minikube start
 - minikube dashboard

 # OBJETOS DE KUBERNETES.
Kubernetes tiene dos tipos de objetos, los basicos y los de nivel superior.

## PARA LA GESTION DE KUBECTL
- Para lanzar un archivo `YAML`
    - `kubectl apply -f ngnix.yaml`
- Para ver objetos:
    - `kubectl get all`
    - `kubectl get deployments`
    - `kubectl get nodes`
    - `kubectl get pods`
- Para eliminar objetos:
    - `kubectl delete deplyment [nombre]`
    - `kubectl delete node [nombre]`
    - `kubectl delete pod [nombre]`
    - `kubectl delete -f [archivo.yaml]`

## TIPOS DE SERVICIOS
- ClusterIP.
    - El servicio recibe una Ip interna a nivel de cluster y hace que el servicio solo sea accesible a nivel de cluster.
- NodePort.
    - Expone el servicio fuera del cluster concatenando la IP del nodo en el que esta el pod y un numero de puerto entre 30000 y 32767, que es el mismo que todos los nodos.
- LoadBalancer.
    - Crea en cloud un balanceador externo con una IP externa asignada.
- ExternalName.
    - Expone el servicio usando un nombre.

Para crear uno de estos servicios hay que exponer el deployment y pasarle parametro el tipo que queremos, por ejemplo yn tipo NodePort.

```
kubectl expose deployment jsonproducer --type=NodePort
```

### NAMESPACES
Todos los objetos creados estan en un mismo espacio, llamado default, si quisieramos cambiar ese espacio para tenerlo todo mejor organizado, tendriamos que crearlos.


Creacion de un NameSpace.
```
kubectl create namespace juan
```

Creacion de un deployment asignando un NameSpace.
```
kubectl run nginxtote --image=nginx --port 80 --namespace juan
```

Cambiar namespace.
```
kubectl config set-context --current --namespace=juan
```

En este ultimo, todo lo que nos pongamos a hacer afecta solo a los objetos que estén en este NamesSpace, todo lo que mostremos será a partir de esto, quiere decir que si en el NameSpace default tenemos un Deployment que que llame hello-minikube, si cambiamos de NameSpace a `juan` por ejemplo, al mostrar nuestros deployments o pods no los motrará.

### ESCALADO DE APLICACION.
A la hora de crear un deployment se puede aumentar el numero de replicas de pods que puede tener ese deploymment, y kubernetes automaticamente irá creando tantos pods como pongamos en las diferentes maquinas que tenga acceso. Gracias a esto es posible la actualizacion de aplucaciones en caliente.

Si ejecutamos un deployment de prueba podemos ver lo siguiente.
```
kubectl apply -f nginx.yaml
```
Nos sale esto:
```
NAME    READY   UP-TO-DATE   AVAILABLE   AGE
nginx   2/2     2            2           17s
```
- READY.
    - Nos dice el numero de replicas(pods) tiene asignados y si estan activos.
- UP-TO-DATE.
    - Nos indica numerod e replicas (pods) que estan actualizados.
- AVAILABLE.
    - El numero de replicas(pods) disponibles.

Ahora otro ejemplo.

Creamos un nuevo Deployment.
```
kubectl run jsonproducer --image=ualmtorres/jsonproducer:v0 --port 80
```

Y lo escalamos diciendo que queremos cuatro replicas.
```
kubectl scale deployments jsonproducer --replicas=4
```

Luego al obtener informacion ya sale que tenemos 4.
```
kubectl get deployments
```
```
NAME           READY   UP-TO-DATE   AVAILABLE   AGE
jsonproducer   4/4     4            4           73m
```

Una vez hecho esto kubernetes ira enviando los equipos que quieran ver esta conexion a los diferentes workers, con un sistema de balanceo que tiene Kubernetes.

Si queremos bajar el numero de pods nos bastaria con poner el mismo comando que usamos para escalar pero con menos numeros, se encargara de borrar los que vea oportunos.

# K3S
## CONFIGURACION DE KUBE
Para configurar El cluster creado por K3s se podria hacer desde el master pero hay una forma mas comoda, que es poder configurarlo desde otra maquina cliente o local, para ello en esa maquina debemos tener instalado Kubectl y algunas cosas que nos pueden servir.

`INSTALACION DE ALGUNOS PAQUETES QUE NOS HACEN FALTA.`
```
apt update && apt install apt-transport-https curl git -y
```

`INSTALACION DE KUBECTL`
```
curl -s https://packages.cloud.google.com/apt/doc/apt-key.gpg | sudo apt-key add -
echo "deb https://apt.kubernetes.io/ kubernetes-xenial main" | sudo tee -a /etc/apt/sources.list.d/kubernetes.list
apt-get update
apt install kubectl -y
```
Cuando este instalado lo configuraremos para que coja todas las credenciales y demas del nodo master, para ello lo hacemos mediante un archivo de configuracion, creamos en nuestra carpeta personal una carpeta que se llame `.kube` de ahi nos conectamos al master mediante scp para coger un archivo de configuracion que se encuentra en `/etc/rancher/k3s` y se llama `k3s.yaml`. Lo cogemos a nuestra maquina con el nombre de config, de la siguiente manera.

```
mkdir .kube
cd .kube
scp [IP_MASTER]:/etc/rancher/k3s/k3s.yaml config
```

Cuando lo tengamos tendremos que editarlo porque esta configurado para la mauqina local, tendremos que decirle donde se encuentra el master, asi que la linea siguiente

```
https://localhost:6443
```
Cambiamos localhost por la Ip del master en mi caso
```
server: https://192.168.122.2:6443
```
Cuando lo tengamos configurado solo nos falta general una variable de entorno para kubernetes y decirle que todos los comandos realizados coga informacion del archivo que acabamos de configurar, de la siguiente forma.
```
export KUBECONFIG=~/.kube/config
```

De este modo si ahora probramos a ejecutar la orden `kubectl get nodes` nos saldra la informacion de los nodos como podria ser esta
```
NAME     STATUS   ROLES    AGE   VERSION
master   Ready    <none>   63m   v1.13.4-k3s.1
node1    Ready    <none>   36m   v1.13.4-k3s.1
node2    Ready    <none>   20m   v1.13.4-k3s.1
```
Ahora podriamos hacer cualquier cosa con kubernetes y el master estaria balanceado entre los nodos

Como prueba podemos crear por ejemplo un deployment con nginx
```
kubectl create deploy nginx --image=nginx
```
Si obtenemos la informacion de los deployments y los pods con la siguiente orden
```
kubectl get deployments,pod
```
Podemos ver algo parecido a esto
```
NAME                          READY   UP-TO-DATE   AVAILABLE   AGE
deployment.extensions/nginx   1/1     1            1           85s

NAME                       READY   STATUS    RESTARTS   AGE
pod/nginx-5c7588df-kklnn   1/1     Running   0          85s
```
Para la conexion a ese pod que hemos creado vamos por medio de la IP del nodo master y creando un servicio de tipo nodeport
```
kubectl expose deploy nginx --port=80 --type=NodePort
```
Cuando esto termine podemos ver en que puerto se ha mapeado mostrando los servicios con kubectl
```
kubectl get services
```
Ahora podriamos probar a escalar la aplicacion y que ejecuten varios pods.
```
kubectl scale --replicas=3 deploy/nginx
```
Y si mostramos los pods
```
NAME                   READY   STATUS    RESTARTS   AGE
nginx-5c7588df-54ls6   1/1     Running   0          37s
nginx-5c7588df-kklnn   1/1     Running   0          12m
nginx-5c7588df-vf8gt   1/1     Running   0          37s
```
Tambien podriamos ver en que nodo se esta ejecutando cada uno.
```
kubectl get pod -o wide
```
```
NAME                   READY   STATUS    RESTARTS   AGE     IP          NODE     NOMINATED NODE   READINESS GATES
nginx-5c7588df-54ls6   1/1     Running   0          2m35s   10.42.1.3   node1    <none>           <none>
nginx-5c7588df-kklnn   1/1     Running   0          14m     10.42.2.2   node2    <none>           <none>
nginx-5c7588df-vf8gt   1/1     Running   0          2m35s   10.42.0.6   master   <none>           <none>
```
Si hacemos un cuarto escalado podriamos ver que una de los nodos tendra dos, en mi caso el nodo1
```
NAME                   READY   STATUS    RESTARTS   AGE     IP          NODE     NOMINATED NODE   READINESS GATES
nginx-5c7588df-4wl5t   1/1     Running   0          9s      10.42.1.4   node1    <none>           <none>
nginx-5c7588df-54ls6   1/1     Running   0          4m56s   10.42.1.3   node1    <none>           <none>
nginx-5c7588df-kklnn   1/1     Running   0          17m     10.42.2.2   node2    <none>           <none>
nginx-5c7588df-vf8gt   1/1     Running   0          4m56s   10.42.0.6   master   <none>           <none>
```

### CREACION DE UN SERVICIO DE TIPO CLUSTER


# DESPLIEGUE DE APLICACIONES MEDIANTE ARCHIVOS YAML.

# INSTALACION DE RANCHER.
```
sudo docker run -d -p 80:80 -p 443:443 rancher/rancher
```