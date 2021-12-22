# API REST de gestión de alojamientos vacacionales #

Esta API REST consiste en implementar las historias de usuario descritas, creando una API que permita a los propietarios crear alojamientos que posteriormente anunciará en portales como Booking.com, Airbnb...

## Historias de usuario

Yo, como propietario de un alojamiento de alquiler vacacional, necesito enviar a una empresa la información de mis alojamientos para poder anunciarlos depués.

  * Necesito poner un nombre comercial al alojamiento, que no supere los 150 caracteres.
  * Es necesario especificar el tipo de alojamiento que es, los portales sólo admiten entre los siguientes tipos: HOUSE, FLAT, VILLA.
  * Tengo que enviar la distribución del alojamiento, con las siguientes especificaciones:
  	* Todos los  alojamientos deben tener mínimo 1 salón, 1 dormitorio y 1 cama.
  * También necesito enviar el máximo de huéspedes que pueden ocupar el alojamiento, teniendo en cuenta que nunca podrán superar el total de camas. 

Yo, como propietario de un alojamiento necesito obtener los datos de mis alojamientos, teniendo en cuenta que sólo quiero obtener los que se han actualizado en fin de semana (sábado o domingo).
  
### A tener en cuenta ###

* También se proporciona un CSV con datos de muestra (data.csv), para que sea más rápido comprobar los métodos GET y se puede usar como fuente de datos para no tener que crear una BBDD y que el sistema revisión pueda comprobar las validaciones.

  * Cada propietario puede tener múltiples alojamientos, los propietarios se diferencian por su ID numérico, si ese ID no es válido, la API devolverá un error.
  * Un propietario sólo obtendrá los alojamientos asociados a su ID.

## API

### POST /user
Carga de propietarios (users) en base de datos y poder referenciar los endpoints que referencien un {user_id}

### POST /user/{user_id}/accommodations

**Body** _required_ El alojamiento a crear.

**Content Type** `application/json`

Ejemplo:

```json
  {
    "trade_name": "Lujoso apartamento en la playa" (string),
	"type": "FLAT" (string),
	"distribution": {
		"living_rooms": 1 (int),
		"bedrooms": 2 (int),
		"beds": 3 (int)
	},
	"max_guests": 3 (int)
  }
```

Respuestas:

* **201 OK** Cuando se crea correctamente.
* **400 Bad Request** Cuando hay algún error en el formato de la llamada, caebceras esperadas o no cumple alguna restricción.

```json
  {
    "id": 1 (int),
    "trade_name": "Lujoso apartamento en la playa" (string),
	"type": "FLAT" (string),
	"distribution": {
		"living_rooms": 1 (int),
		"bedrooms": 2 (int),
		"beds": 3 (int)
	},
	"max_guests": 3 (int),
	"updated_at": "2021-12-01" (string-date)
  }
```
* * *

### PUT /user/{user_id}/accommodations/{id}

**Body** _required_ El alojamiento a actualizar.

**Content Type** `application/json`

Ejemplo:

```json
  {
    "id": 1 (int),
    "trade_name": "Lujoso apartamento en la playa" (string),
	"type": "FLAT" (string),
	"distribution": {
		"living_rooms": 1 (int),
		"bedrooms": 2 (int),
		"beds": 3 (int)
	},
	"max_guests": 3 (int)
  }
```

Respuestas:

* **200 OK** Cuando se actualiza correctamente.
* **400 Bad Request** Cuando hay algún error en el formato de la llamada, caebceras esperadas o no cumple alguna restricción.

```json
  {
    "id": 1 (int),
    "trade_name": "Lujoso apartamento en la playa" (string),
	"type": "FLAT" (string),
	"distribution": {
		"living_rooms": 1 (int),
		"bedrooms": 2 (int),
		"beds": 3 (int)
	},
	"max_guests": 3 (int),
	"updated_at": "2021-12-01" (string-date)
  }
```
* * *

### GET /user/{user_id}/accommodations

Respuestas:

* **200 OK** Cuando se obtiene correctamente.
* **400 Bad Request** Cuando hay algún error en el formato de la llamada, caebceras esperadas o no cumple alguna restricción.

```json
[  {
    "id": 1 (int),
    "trade_name": "Lujoso apartamento en la playa" (string),
	"type": "FLAT" (string),
	"distribution": {
		"living_rooms": 1 (int),
		"bedrooms": 2 (int),
		"beds": 3 (int)
	},
	"max_guests": 3 (int),
	"updated_at": "2021-12-01" (string-date)
  },
  {
    "id": 2 (int),
    "trade_name": "Villa en la montaña" (string),
	"type": "VILLA" (string),
	"distribution": {
		"living_rooms": 3 (int),
		"bedrooms": 8 (int),
		"beds": 12 (int)
	},
	"max_guests": 12 (int),
	"updated_at": "2021-12-01" (string-date)
  },
]
```

### GET user/{user_id}/accommodationsdb
Obtiene, al igual que el endpoint anterior todos los alojamientos de un propietario que cumpla las restricciones desde la base de datos en lugar de obtenerlos del archivo data.csv.

* * *
