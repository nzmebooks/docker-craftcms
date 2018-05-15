# Ansible

These are notes about installing our docker-compose stack onto a Ubuntu server using ansible.

## Set our group variables in `group_vars/remote.yml`:

```
---

docker_users: ["deploy", "docker"]
```

## Set our remote server IP in `hosts`:

```
[remote]
<IP> ansible_connection=ssh ansible_ssh_user=root
```


## Usage

Installing Ansible

    sudo pip install ansible

Install the roles using ansible-galaxy:

    ansible-galaxy install -r requirements.yml -p ./roles

Ensure we can connect to our host:

    ansible -m ping -u root -i hosts all
    ansible -m shell -a 'free -m' <SERVER_IP> -i hosts


## Running the playbook to create users:

    ansible-playbook playbook-ansible-users.yml -i hosts

    # Check that we can access the server as the deploy user
    ssh deploy@<SERVER_IP>
    sudo -s

Now that we've created our user roles, ensure in the `hosts` file that we use `ansible_ssh_user=deploy`

```
[remote]
<IP> ansible_connection=ssh ansible_ssh_user=deploy
```


## Running the playbook to install common packages:

    ansible-playbook playbook-ansible-common.yml -i hosts

    # Check that our packages have been installed
    ansible -m shell -a 'zip -h' remote -i hosts


## Run our playbook to install docker and docker-compose

    ansible-playbook playbook-ansible-docker.yml -i hosts

    # Check that our packages have been installed
    ansible -m shell -a 'docker images' remote -i hosts


## Set up DNS

You'll want to ensure DNS is set in `/etc/hosts` pointing to the server IP:

```
# Adds the following:
# 192.168.1.79	traefik.example.com
# 192.168.1.79	portainer.example.com
# 192.168.1.79	web.example.com

IP=192.168.1.79
DOMAIN=example.com
sudo -- sh -c -e "echo '$IP\ttraefik.$DOMAIN' >> /etc/hosts";
sudo -- sh -c -e "echo '$IP\tportainer.$DOMAIN' >> /etc/hosts";
sudo -- sh -c -e "echo '$IP\tweb.$DOMAIN' >> /etc/hosts";
```

## Run our playbook to install and start our docker-compose stack

    ansible-playbook playbook-ansible-docker-compose.yml -i hosts
