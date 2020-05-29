window.visNetworkData = [];
window.visNetworkRaw = [];
window.visualNetwork = visualNetwork = (url, customOptions = null, customScale = null) => {
    fetch(url)
        .then((res => res.json()))
        .then((data) => {
            if (!data.nodes.length) {
                Swal.fire('Oops!', 'Tidak ada data', 'error');
            }
            window.visNetworkRaw = data;

            function draw(data) {
                const container = document.getElementById('network');
                const options = customOptions ? customOptions : {
                    nodes: {
                        shapeProperties: {
                            interpolation: false    // 'true' for intensive zooming
                        }
                    },
                    layout: {improvedLayout: false},
                    physics: {
                        solver: 'forceAtlas2Based',
                        timestep: 0.35,
                        stabilization: {
                            enabled: true,
                            fit: true,
                            iterations: 100,
                            updateInterval: 25
                        }
                    }
                };

                const network = new vis.Network(container, data, options);


                network.on("stabilizationProgress", function (params) {
                    const maxWidth = 280;
                    const minWidth = 20;
                    const widthFactor = params.iterations / params.total;
                    const width = Math.max(minWidth, maxWidth * widthFactor);

                    document.getElementById('networkBar').style.width = width + 'px';
                    document.getElementById('networkText').innerHTML = Math.round(widthFactor * 100) + '%';
                });
                network.once("stabilizationIterationsDone", function () {
                    document.getElementById('networkText').innerHTML = '100%';
                    document.getElementById('networkBar').style.width = '279px';
                    document.getElementById('networkLoadingBar').style.opacity = 0;
                    // really clean the dom element
                    setTimeout(function () {
                        document.getElementById('networkLoadingBar').style.display = 'none';
                    }, 500);

                    const scaleOption = {scale: customScale ? customScale : 0.05};
                    network.moveTo(scaleOption);
                    exportNetwork()
                });


                function addConnections(elem, index) {
                    // need to replace this with a tree of the network, then get child direct children of the element
                    elem.connections = network.getConnectedNodes(index);
                }

                function objectToArray(obj) {
                    return Object.keys(obj).map(function (key) {
                        obj[key].id = key;
                        return obj[key];
                    });
                }

                function exportNetwork() {
                    const nodes = objectToArray(network.getPositions());

                    nodes.forEach(addConnections);
                    window.visNetworkData = nodes;
                    container.classList.add('done');
                    // pretty print node data
                    // console.log(JSON.stringify(nodes, undefined, 2));
                }

            }

            const nodes = new vis.DataSet(data.nodes);
            const edges = new vis.DataSet(data.edges);

            const edgesView = new vis.DataView(edges);
            const nodesView = new vis.DataView(nodes);

            draw({nodes: nodesView, edges: edgesView});

        })
        .catch((err) => console.log("Someting went wrong", err));
};
window.sekitarSocket = sekitarSocket = {
    connect: () => {
        function authSocket(key, value) {
            return btoa(`${key}:${value}`);
        }

        return io(process.env.MIX_SEKITAR_SOCKET_URL, {
            path: '/sekitar',
            transports: ['websocket'],
            query: {
                'token': authSocket(process.env.MIX_SEKITAR_SOCKET_KEY, process.env.MIX_SEKITAR_SOCKET_VALUE),
            }
        });
    },
};
