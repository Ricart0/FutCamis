function cargarCarrito() {
    if (!localStorage.getItem('carrito')) {
        localStorage.setItem('carrito', JSON.stringify([]));
    }
}

function guardarCarrito(carrito) {
    localStorage.setItem('carrito', JSON.stringify(carrito));
}

function agregarAlCarrito(id, nombre, precio, cantidad) {
    cargarCarrito();
    let carrito = JSON.parse(localStorage.getItem('carrito'));

    let index = carrito.findIndex(item => item.id === id);
    let stockDisponible = parseInt(document.getElementById('cantidad_' + id).getAttribute('max'));

    if (index !== -1) {
        // Si el producto ya está en el carrito, sumar la cantidad
        if (carrito[index].cantidad + cantidad > stockDisponible) {
            alert("No hay suficiente stock disponible para añadir " + cantidad + " unidades de " + nombre + " al carrito.");
            return;
        } else {
            carrito[index].cantidad += cantidad;
        }
    } else {
        // Si el producto no está en el carrito, agregarlo
        if (cantidad > stockDisponible) {
            alert("No hay suficiente stock disponible para añadir " + cantidad + " unidades de " + nombre + " al carrito.");
            return;
        } else {
            carrito.push({ id, nombre, precio, cantidad });
        }
    }

    alert("Producto añadido al carrito: " + nombre + ". Cantidad: " + cantidad);
    guardarCarrito(carrito);
    cargarCarritoEnNavbar();
}


function eliminarDelCarrito(id) {
    cargarCarrito();
    let carrito = JSON.parse(localStorage.getItem('carrito'));

    carrito = carrito.filter(item => item.id !== id);

    guardarCarrito(carrito);
    cargarCarritoEnNavbar();
    mostrarProductosCarrito();
}

function vaciarCarrito() {
    localStorage.removeItem('carrito');
    cargarCarritoEnNavbar();
    mostrarProductosCarrito();
    
}

function mostrarCarrito() {
    cargarCarrito();
    let carrito = JSON.parse(localStorage.getItem('carrito'));

    console.log(carrito);
}

function cargarCarritoEnNavbar() {
    let carrito = JSON.parse(localStorage.getItem('carrito'));
    let carritoCount = 0;
    if (carrito) {
        carrito.forEach(item => {
            carritoCount += item.cantidad;
        });
    }
    document.getElementById('carritoCount').innerText = carritoCount;
}

function cargarElementosCarrito() {
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    let carritoItems = document.getElementById('carritoItems');
    carritoItems.innerHTML = '';

    if (carrito.length > 0) {
        carrito.forEach(item => {
            let li = document.createElement('li');
            li.classList.add('dropdown-item');
            li.innerHTML = `<button class="btn btn-danger btn-sm" onclick="eliminarDelCarrito(${item.id})">Eliminar</button> ${item.nombre} - ${item.cantidad} x ${item.precio}€ `;
            
            let btnRestar = document.createElement('button');
            btnRestar.textContent = '-';
            btnRestar.classList.add('btn', 'btn-secondary', 'btn-sm', 'mx-1');
            btnRestar.onclick = function() {
                modificarCantidad(item.id, 'restar');
            };
            li.appendChild(btnRestar);

            let btnSumar = document.createElement('button');
            btnSumar.textContent = '+';
            btnSumar.classList.add('btn', 'btn-secondary', 'btn-sm');
            btnSumar.onclick = function() {
                modificarCantidad(item.id, 'sumar');
            };
            li.appendChild(btnSumar);
            
            carritoItems.appendChild(li);
        });

        let total = calcularTotalCarrito(carrito);
        let totalElement = document.createElement('li');
        totalElement.classList.add('dropdown-item');
        totalElement.innerHTML = `<strong>Total: ${total.toFixed(2)}€</strong>`;
        carritoItems.appendChild(totalElement);

        let vaciarCarritoBtn = document.createElement('li');
        vaciarCarritoBtn.classList.add('dropdown-item');
        vaciarCarritoBtn.innerHTML = '<button class="btn btn-danger btn-sm" onclick="vaciarCarrito()">Vaciar Carrito</button>';
        carritoItems.appendChild(vaciarCarritoBtn);
    } else {
        carritoItems.innerHTML = '<li class="dropdown-item">El carrito está vacío</li>';
    }
}

function modificarCantidad(id, operacion) {
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    let index = carrito.findIndex(item => item.id === id);
    console.log("Hola")
    if (index !== -1) {
        if (operacion === 'restar' && carrito[index].cantidad > 0) {
            carrito[index].cantidad -= 1;
            alert("Se ha restado 1 producto");
        } else if (operacion === 'sumar') {
            carrito[index].cantidad += 1;
            alert("Se ha sumado 1 producto");
        }
    }

    localStorage.setItem('carrito', JSON.stringify(carrito));
    cargarElementosCarrito();
    cargarCarritoEnNavbar();
    mostrarProductosCarrito();
    guardarCarrito(carrito);
}

function calcularTotalCarrito(carrito) {
    let total = 0;
    carrito.forEach(item => {
        total += item.precio * item.cantidad;
    });
    return total;
}

function finalizarCompra(usuarioLogueado) {
    // Verificar si el usuario está autenticado
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    if (usuarioLogueado) {
        // El usuario está autenticado, enviar el carrito
        console.log("si")
        EnviarCarrito('RecogerCarritoServlet.html', 'destino', carrito);
    } else {
        // El usuario no está autenticado, redirigir a la página de inicio de sesión
        console.log("no")
        window.location.href = 'loginUsuario.jsp';
    }
}


function invokeScript(divid) {
	var scriptObj = divid.getElementsByTagName("SCRIPT");
	var len = scriptObj.length;
	for (var i = 0; i < len; i++) {
		var scriptText = scriptObj[i].text;
		var scriptFile = scriptObj[i].src
		var scriptTag = document.createElement("SCRIPT");
		if ((scriptFile != null) && (scriptFile != "")) {
			scriptTag.src = scriptFile;
		}
		scriptTag.text = scriptText;
		if (!document.getElementsByTagName("HEAD")[0]) {
			document.createElement("HEAD").appendChild(scriptTag)
		}
		else {
			document.getElementsByTagName("HEAD")[0].appendChild(scriptTag);
		}
	}
}

function nuevaConexion() {
	var xmlhttp = false;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch (e) {
		try {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch (E) {
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

function EnviarCarrito(url, capa, valores) {
	var contenido = document.getElementById(capa);
	conexion = nuevaConexion();
	conexion.open("POST", url, true);
	console.log("Envia");
	conexion.onreadystatechange = function () {
		if ((conexion.readyState == 4) && (conexion.status == 200)) {
			contenido.innerHTML = conexion.responseText;
			invokeScript(document.getElementById(capa));
		}
	}
    conexion.setRequestHeader('Content-Type', 'application/json; charset=utf-8');
	conexion.send(JSON.stringify(valores));
}


function mostrarProductosCarrito() {
    
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    let carritoTableBody = document.getElementById('carritoTableBody');
    carritoTableBody.innerHTML = '';
    

    if (carrito.length > 0) {
        carrito.forEach(item => {
            let row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.nombre}</td>
                <td>${item.cantidad}</td>
                <td>${item.precio}€</td>
                <td>${(item.cantidad * item.precio).toFixed(2)}€</td>
                <td>
                <button class="btn btn-secondary btn-sm" onclick="modificarCantidad(${item.id}, 'restar')">-</button>
                <button class="btn btn-secondary btn-sm" onclick="modificarCantidad(${item.id}, 'sumar')">+</button>
                <button class="btn btn-danger btn-sm" onclick="eliminarDelCarrito(${item.id})">Eliminar</button>
                </td>
            `;
            carritoTableBody.appendChild(row);
        });

        // Actualizar el total del carrito
        let total = calcularTotalCarrito(carrito);
        let totalRow = document.createElement('tr');
        totalRow.innerHTML = `<td colspan="3"><strong>Total:</strong></td><td><strong>${total.toFixed(2)}€</strong></td><td></td>`;
        carritoTableBody.appendChild(totalRow);
    } else {
        let emptyRow = document.createElement('tr');
        emptyRow.innerHTML = `<td colspan="5">El carrito está vacío</td>`;
        carritoTableBody.appendChild(emptyRow);
    }
}

function actualizarCarrito() {
    mostrarProductosCarrito();
}


window.onload = function() {
    mostrarProductosCarrito();
    cargarCarritoEnNavbar();
};

cargarCarrito();
