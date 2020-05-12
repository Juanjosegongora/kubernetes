# KUBERNETES.
## JUAN JOSE GONGORA CONTRERAS

# QUE ES KUBERNETES.
Kubernetes en una plataforma de codido abierto para el despligue, escalado y gestion de aplicaciones contenedorizadas.

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
## INSTALACIONDE SERVIDOR DE K3S
Para la instalacion del servidor de K3S necesitamos tener como minio un master y un worker, yo lo hare con un Master y dos workers.

Cuando las maquinas esten listas tenemos que empezar desde la maquina `master` a realizar las operaciones, procedemos a descargar el paquete K3S, como K3S por asi decirlo es un comando, lo que haremos sera descargarlo directamente en una ruta del PATH como por ejemplo `/usr/local/bin`

```
cd /usr/lcoal/bin
```
Ahora descargamos el paquete.
```
wget https://github.com/rancher/k3s/releases/download/v0.2.0/k3s
```
Le proporcionamos permisos de ejecucion.
```
chmod +x k3s
```
Para comenzar a desplegar el cluster con el nodo master tenemos que poner un comando muy sencillo.

```
k3s server &
```
Con esto el master ya esta listo y el cluster ya esta funcionando y podriamos ejecutar comandos `kubectl` siempre con el comando K3S por delante un ejemplo.
```
k3s kubectl get nodes
```
Y nos puede salir algo parecido a esto.
```
NAME     STATUS     ROLES    AGE   VERSION
master   Ready      <none>   27h   v1.13.4-k3s.1
```
Con esto el master esta listo, ahora tenemos que implementar los workers al cluster, empezamos accediendo a alguno de ellos y descargando el paquete y proporcionando permisos como en el `master` cuando todo eso este listo tenemos que ejecutar una instruccion.
```
k3s agent --server https://[IP_MAASTER]]:6443 --token [TOKEN_MASTER]
```
Nos encontramos con que no sabemos el token, esto lo podemos sacar en la maquina master en el siguiente archivo `/var/lib/rancher/k3s/server/node-token`

```
cat /var/lib/rancher/k3s/server/node-token
```
El conteido se ese archivo sera el token que necesitamos entonces el comando de arriba en mi caso quedaria algo asi.

```
k3s agent --server https://192.168.122.2:6443 --token K10d6ccdb07ebdb6594f22aac004bfa36d6c0ed04d584bb295b8cdaf5c57ac582c5::node:5a2356bfd0d35a9fd2dbc55041727a08
```
Tendremos que hacer lo mismo con el otro worker y de esta manera mediante el nodo master podriamos ejecutar las intruccioner `kubectl`

```
k3s kubectl get nodes
```
```
NAME     STATUS   ROLES    AGE   VERSION
master   Ready    <none>   63m   v1.13.4-k3s.1
node1    Ready    <none>   36m   v1.13.4-k3s.1
node2    Ready    <none>   20m   v1.13.4-k3s.1
```

## CONFIGURACION DE KUBE EN UNA MAQUINA PARA MANEJAR EL CLUSTER.
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

# INSTALACION DE CLUSTER DE KUBERNETES EN AWS CON RANCHER.
## INSTALACION DE RANCHER
Para esto lanzaremos una maquina ec2 en nuestro AWS 

NOTA: `PARA QUE RANCHER FUNCIONE MINIMO TIENE QUE SER UNA MAQUINA DE t2.small`

Cuando esa maquina este disponible tendremos que actualizar la maquina e instalar docker.
```
apt update && apt install docker.io -y
```
Cuando lo tengamos bastara con lanzar un contendor en docker y podremos acceder a rancher desde el navegador.
```
docker run -d -p 80:80 -p 443:443 rancher/rancher
```
Nos pedira que necesitamos proporcionar una clave para acceder al administrador de rancher

NOTA: `RECOMIENDO QUE AUNQUE SEA UNA PRUEBA LA CLAVE SEA SEGURA PORQUE MAS TARDE LE DAREMOS A RANCHER NUSTRAS CEDENCIALES Y CUALQUIERA PODRIA HACER COSAS SI ACCEDEN CON UNA CLAVE FACIL`.

Despues de eso nos dejara acceder a rancher perfectamente.

## CONFIGURACION DE PERMISOS DE AWS.

Para que todo lo que queremos hacer funcione necesitamos gestionar un servicio que nos ofrece AWS que se trata de IAM, con esto crearemos un usuario para rancher que le permita el acceso a nuestro AWS.

### CREACION DE UN USUARIO EN AWS Y PROPORCIONARLE PERMISOS.

Lo primero es crear el usuario que eso debemos de ir a los servicios de AWS y buscar IAM, alli a la seccion de usuario y como no tenemos ningun usuario nos sugiere crear uno.

Rellenamos el nombre de usuario y despues tenemos que seleccionar que tipo de acceso tendra ese usuario, seleccionaremos el segundo, que s mediante consola. La clave podemos escoger una o que la genere automatica, sugiero automatica.

![](images/rancher/usuario.png)

Seguidamente nos perdira que lo metamos en un grupo para darle permisos, esto por ahora lo dejamos, lo haremos mas tarde. Seguimos creando el usuario dandole a siguiente hasta terminar todos los paso no hay que rellenar nada mas.

Cuando hagamos esto pasaremos a crear politicas y roles de IAM (`Permisos`)

Crearemos una politica para el `controlpane` de nuestro fururo cluster de kubernetes para crear una politica iremos a IAM, politica,crear politica, hay dos formas de hacer esto que es ir seleccionando que permisos queremos proporcionar y el otro mediante un archivo JSON que es lo mismo que lo otro pero es en codigo os mostrare como seria el de codigo, asi es mas facil para que lo copieis

```
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Effect": "Allow",
            "Action": [
                "autoscaling:DescribeAutoScalingGroups",
                "autoscaling:DescribeLaunchConfigurations",
                "autoscaling:DescribeTags",
                "ec2:DescribeInstances",
                "ec2:DescribeRegions",
                "ec2:DescribeRouteTables",
                "ec2:DescribeSecurityGroups",
                "ec2:DescribeSubnets",
                "ec2:DescribeVolumes",
                "ec2:CreateSecurityGroup",
                "ec2:CreateTags",
                "ec2:CreateVolume",
                "ec2:ModifyInstanceAttribute",
                "ec2:ModifyVolume",
                "ec2:AttachVolume",
                "ec2:AuthorizeSecurityGroupIngress",
                "ec2:CreateRoute",
                "ec2:DeleteRoute",
                "ec2:DeleteSecurityGroup",
                "ec2:DeleteVolume",
                "ec2:DetachVolume",
                "ec2:RevokeSecurityGroupIngress",
                "ec2:DescribeVpcs",
                "elasticloadbalancing:AddTags",
                "elasticloadbalancing:AttachLoadBalancerToSubnets",
                "elasticloadbalancing:ApplySecurityGroupsToLoadBalancer",
                "elasticloadbalancing:CreateLoadBalancer",
                "elasticloadbalancing:CreateLoadBalancerPolicy",
                "elasticloadbalancing:CreateLoadBalancerListeners",
                "elasticloadbalancing:ConfigureHealthCheck",
                "elasticloadbalancing:DeleteLoadBalancer",
                "elasticloadbalancing:DeleteLoadBalancerListeners",
                "elasticloadbalancing:DescribeLoadBalancers",
                "elasticloadbalancing:DescribeLoadBalancerAttributes",
                "elasticloadbalancing:DetachLoadBalancerFromSubnets",
                "elasticloadbalancing:DeregisterInstancesFromLoadBalancer",
                "elasticloadbalancing:ModifyLoadBalancerAttributes",
                "elasticloadbalancing:RegisterInstancesWithLoadBalancer",
                "elasticloadbalancing:SetLoadBalancerPoliciesForBackendServer",
                "elasticloadbalancing:AddTags",
                "elasticloadbalancing:CreateListener",
                "elasticloadbalancing:CreateTargetGroup",
                "elasticloadbalancing:DeleteListener",
                "elasticloadbalancing:DeleteTargetGroup",
                "elasticloadbalancing:DescribeListeners",
                "elasticloadbalancing:DescribeLoadBalancerPolicies",
                "elasticloadbalancing:DescribeTargetGroups",
                "elasticloadbalancing:DescribeTargetHealth",
                "elasticloadbalancing:ModifyListener",
                "elasticloadbalancing:ModifyTargetGroup",
                "elasticloadbalancing:RegisterTargets",
                "elasticloadbalancing:SetLoadBalancerPoliciesOfListener",
                "iam:CreateServiceLinkedRole",
                "kms:DescribeKey"
            ],
            "Resource": [
                "*"
            ]
        }
    ]
}
```
Lo que estamos haciendo en esta politica es darle a todo aquel que este asociado a esta politica el poder de los permisos como `ejecutar instancias, borrarlas, crear security groups y todo lo relacionado para la gestion que necesita rancher para la creacion del clsuter`

Despues revisamos la politica le ponemos un nombre por ejemplo `controlpane_policy` y la creamos.

Ahora tendremos que hacer una para el `etcd y worker`, el mismo procesp pero el contenido del JSON es diferente, es este

```
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Effect": "Allow",
            "Action": [
                "ec2:DescribeInstances",
                "ec2:DescribeRegions",
                "ecr:GetAuthorizationToken",
                "ecr:BatchCheckLayerAvailability",
                "ecr:GetDownloadUrlForLayer",
                "ecr:GetRepositoryPolicy",
                "ecr:DescribeRepositories",
                "ecr:ListImages",
                "ecr:BatchGetImage"
            ],
            "Resource": "*"
        }
    ]
}
```
La revisamos y por ejemplo la podemos llamar `etcd_worker_policy`

Lo siguiente es crear roles y asociarlos cada uno a su politica. Para ellos nos vamos a IAM, roles y nuevo rol. Aqui asociaremos la politica para cada uno de los roles, por ejemplo si estamos creando el rol de `controlpane` le asociamos la politica de `controlpane_policy` y a ese rol podemos llamarle `controlpane_role`. Creamos tambien el rol para `etcd_worker_policy` y la llamamos `etcd_worker_role`.

Importante crear otra politica que una estas dos, es decir, creamos otro rol que y le asociamos `controlpane_policy` y `etcd_worker_policy` y por ejemplo podemos llamarla `all_role`

Ahora volveriamos a la creacion de politicas para crear la ultima politica que nos hace falta `PASSROLE`

Necesitaremos lo siguiente
- ID de nuestra cuenta de amazon, esto lo podemos encontrar dando en nuestro nombre de usuario arriba a la derecha, mi cuenta.
- REGION esto lo podemos encontrar arriba a la derecha donde pone al lado de nuestro nombre de usuario encontraremos el estado donde estamos y si pulsamos nos dira nuestra region, en mi caso es `us-east-2`

En el siguiente archivo JSON hay datos que hay que cambiar dependiendo de nuestra `REGION, ID_CUENTA, NOMBRE DE ROLES`

```
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Sid": "VisualEditor0",
            "Effect": "Allow",
            "Action": [
                "ec2:AuthorizeSecurityGroupIngress",
                "ec2:Describe*",
                "ec2:ImportKeyPair",
                "ec2:CreateKeyPair",
                "ec2:CreateSecurityGroup",
                "ec2:CreateTags",
                "ec2:DeleteKeyPair"
            ],
            "Resource": "*"
        },
        {
            "Sid": "VisualEditor1",
            "Effect": "Allow",
            "Action": [
                "ec2:RunInstances",
                "iam:PassRole"
            ],
            "Resource": [
                "arn:aws:ec2:REGION::image/ami-*",
                "arn:aws:ec2:REGION:ID_CUENTA:instance/*",
                "arn:aws:ec2:REGION:ID_CUENTA:placement-group/*",
                "arn:aws:ec2:REGION:ID_CUENTA:volume/*",
                "arn:aws:ec2:REGION:ID_CUENTA:subnet/*",
                "arn:aws:ec2:REGION:ID_CUENTA:key-pair/*",
                "arn:aws:ec2:REGION:ID_CUENTA:network-interface/*",
                "arn:aws:ec2:REGION:ID_CUENTA:security-group/*",
                "arn:aws:iam::ID_CUENTA:role/etcd_worker_role",
                "arn:aws:iam::ID_CUENTA:role/controlpane_role"
            ]
        },
        {
            "Sid": "VisualEditor2",
            "Effect": "Allow",
            "Action": [
                "ec2:RebootInstances",
                "ec2:TerminateInstances",
                "ec2:StartInstances",
                "ec2:StopInstances"
            ],
            "Resource": "arn:aws:ec2:REGION:ID_CUENTA:instance/*"
        }
    ]
}
```

Cuando tengamos esto solo nos faltaria volver a los roles y crear el rol para la politica de `PASSROLE` como hemos hecho con las politicas anteriores.

Lo siguiente como nos habiamos dejado el usuario sin darle los permisos ahora es el momento ya que los acabamos de hacer, nos tendriamos que ir a IAM, usuarios, 'nuestro usuario', agregar permisos, crear grupo nos ssaldra la lista de politicas, aqui agregamos las tres politicas que hemos creado antes `controlpane_policy, etcd_worker_policy` y `passrole_policy`. Por ejemplo le podemos llamar `policy_group`

## CREACION DE CLUSTER EN RANCHER.

### AGREGAR CREDENCIALES DE AMAZON A RANCHER.
Cuando nos vayamos a rancher tenemos que hacer dos cosas previas darle credenciales de AWS del usuario que hemos creado, en rancher nos vamos a `cloud credentials`, `add cloud credential`.

Nos pedira el nombre, tipo de nube(Amazon), nuestra region(Como la que hemos puesto en las politicas), access key y secret key.

Para con seguir el `access key` y `secret key` debemos de ir a amazon y entrar en IAM, usuarios, credenciales de seguridad y le debemos dar a crear una nueva clave de acceso entonces amazon nos dara ambas cosas y las ponemos en rancher.

### CONFIGURAR NODE TEMPLATES
Lo que vamos a configurar acontinuacion es las caracteristicas de maquinas de los nodos, al darle a agregar nodo nos dira que tipo de Nube que es amazon, la region y nuestra cloud credentials las zonas de red que hay en nuestra region ponemos las que queramos y la subred comgemos la que tenemos por defecto. Cuando le demos a siguiente tocaran los puertos abiertos para la maquina que ponemos que default de rancher esto abrira los que rancher crea oportunos.

Lo siguiente son las especificaciones de la maquina que podemos escoger la instancia que queramos.
- AMI: Aqui tendremos que poner una ami de rancher que la ami cambia segun al region, dejo un enlace para que se vea la ami segun tu region
```
https://github.com/rancher/os/blob/master/README.md/#user-content-amazon
```
- IAM INSTANCE PROFILE NAME: Aqui ponemos el nombre del rol en el que hemos juntado las dos politicas antes, el que yo he llamado `all_role`
- SSH User: Aqui tenemos que poner `rancher`

Y por ultimo poner el nombre del node. 

Para que sirve estod e NODE template realmente. por ejemplo si queremos que el master o los worker sean maquinas diferentes connfiguramos la maquina de una forma u otra creando varios.

### CREACION DEL CLUSTER
Ya por ultimo crear el cluster, nos vamos a cluster y add cluster, seleccionamos el ec2 y le ponemos nomrbe a nuestro cluster

Ponemos un prefiejo a la maquina, seleccionamos el template que hayamos creado y luego tenemos que seleccionar que nodos seran `etcd, controlpane o worker` podemos poner hasta cuantas queremos crear.

Luego mas abajo nos pondra que cloud provider, deberemos seleccionar que amazon y damos en create y el cluster se empieza a crear.

## LEVANTAR ALGUNA IMAGEN EN EL CLUSTER QUE HEMOS CREADO.
Para hacer un deploy tenemos que irnos a nuestro cluster, system. Desde ahi a la derecha nos sale deploy y seguidamente la configuracion del deploy como su nombre, su docker image, si queremos crearle algun servicio y tal.

Mas abajo tendremos las variables que tendra la imagen de docker, los volumenes que podemos usar con `sercret o config map`. Como un archivo yaml pero en modo grafico, si queremos importat nuesstro archivo porque ya lo tenemos listo tambien lo podemos hacer. Y para finalizar simplemente le damos a launch.

![](images/rancher/creacion_pod.png)

Si por ejemplo hemos creado un nodeport y queremos acceder a esa maquina desde el exterior en el menu de System nos sale nuestro nuevo pod que si nos fijamos debajo de su nombre tenemos el puerto que ha generado rancher random o si hemos puesto nosotros uno manual, solo tenemos que o bien darle ahi y nos lleva automaticamente o tambien si queremos acceder nosotros con ip tendriamos que coger la IP del MASTER junto a ese puerto.

![](images/rancher/pod_creado.png)

## AUTO ESCALADO HORIZONTAL CON RANCHER.
Esto se hace mediante HPA, en los pods hay que limitarles los milicpu, sino no nos dejara ponerle un HPA.

Al crear un Deploy tenemos que ver las opciones avanzadas de la creacion e irnos a `Security & Host config` y aqui bajar hasta encontrar CPU reservation y limitarle las CPUs. Si lo dejamos sin reservar HPA no podra realizar el escalado, para probar podemos reservar pocas, por ejemplo 100 o 200 y darle a Launch.

![](images/rancher/reservar_cpu.png)

Seguidamente tenemos que ir a System, resources, HPA, add HPA.

Le pondremos un nombre, seleecionamos en que namespace se encuentra, y nuesto workload(pod), nuestras replicas como minimo y maximo.

Y ya abajo la metrica, que puede ser o de CPU o re memoria RAM, recomiendo la de porcentaje de CPU

![](images/rancher/hpa_listo.png)

Ahora, como podemos probar esto, muy faicl, con la herramienta de apache `apache benchmark` que sino lo tienes instalado en tu linux basta con lo siguiente.

```
apt isntall apache2_utils
```

Este comando funciona de la siguiente manera, envia un total de peticiones, y tambien las que le digamos simultaneamente, se veria de la siguiente forma.
```
ab -n100000 -c100 http://IP_SERVER/
```

En este casp envia 100000 peticiones y va enviando 100 simultaneamente, a la direccion que tengamos nuestro pod (Direccion NodePort)

Cuando hagamos esto el HPA si ve que sobrepasa el limite establecido lo ira escalando cuanto sea necesario hasta el maximo que pongamos.

![](images/rancher/limite_hpa.png)

En la siguiente imagen lo he sobrepasado y segun el porcentaje, ha tenido que crear otros 4 pods identicos.