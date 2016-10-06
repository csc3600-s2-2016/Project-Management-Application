# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

    config.vm.box = "csc3600.box"
    
    config.vm.network "private_network", ip: "192.168.33.10"
    config.vm.hostname = "scotchbox"
    config.vm.synced_folder "./", "/var/www", :mount_options => ["dmode=777", "fmode=666"]
    
    # if you want to run gul watch on startup uncomment this
    #config.trigger.after :up do
    #    run_remote "cd /var/www/taskmanager && gulp watch"
    #end
    # Optional NFS. Make sure to remove other synced_folder line too
    #config.vm.synced_folder ".", "/var/www", :nfs => { :mount_options => ["dmode=777","fmode=666"] }
    config.vm.provision "shell", privileged: false, path: "./.provisionVagrant.bash"
end
