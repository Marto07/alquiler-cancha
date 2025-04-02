<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Wizard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
    <style>
        .step-container {
            display: none;
        }
        .step-container.active {
            display: block;
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="container mt-5">
            <form @submit.prevent="submitForm">
                <!-- Paso 1: Datos personales -->
                <div class="step-container" v-bind:class="{ 'active': currentStep === 1 }">
                    <h4 class="mb-4">Paso 1: Datos personales</h4>
                    <div class="form-group">
                        <label for="firstName">Nombre</label>
                        <input type="text" id="firstName" v-model="form.firstName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Apellido</label>
                        <input type="text" id="lastName" v-model="form.lastName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="document">Documento</label>
                        <input type="text" id="document" v-model="form.document" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="documentType">Tipo de documento</label>
                        <select id="documentType" v-model="form.documentType" class="form-control" required>
                            <option value="dni">DNI</option>
                            <option value="passport">Pasaporte</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="gender">Sexo</label>
                        <select id="gender" v-model="form.gender" class="form-control" required>
                            <option value="male">Masculino</option>
                            <option value="female">Femenino</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary mt-3" @click="nextStep">Siguiente</button>
                </div>

                <!-- Paso 2: Datos del usuario -->
                <div class="step-container" v-bind:class="{ 'active': currentStep === 2 }">
                    <h4 class="mb-4">Paso 2: Datos del usuario</h4>
                    <div class="form-group">
                        <label for="username">Nombre de usuario</label>
                        <input type="text" id="username" v-model="form.username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input type="email" id="email" v-model="form.email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" v-model="form.password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirmar Contraseña</label>
                        <input type="password" id="confirmPassword" v-model="form.confirmPassword" class="form-control" required>
                    </div>
                    <button type="button" class="btn btn-secondary mt-3" @click="prevStep">Anterior</button>
                    <button type="submit" class="btn btn-primary mt-3">Finalizar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        new Vue({
            el: '#app',
            data() {
                return {
                    currentStep: 1,
                    form: {
                        firstName: '',
                        lastName: '',
                        document: '',
                        documentType: 'dni',
                        gender: 'male',
                        username: '',
                        email: '',
                        password: '',
                        confirmPassword: ''
                    }
                };
            },
            methods: {
                nextStep() {
                    if (this.currentStep < 2) {
                        this.currentStep++;
                    }
                },
                prevStep() {
                    if (this.currentStep > 1) {
                        this.currentStep--;
                    }
                },
                submitForm() {
                    if (this.form.password !== this.form.confirmPassword) {
                        alert('Las contraseñas no coinciden');
                        return;
                    }

                    console.log('Formulario enviado:', this.form);
                    alert('Formulario enviado exitosamente');
                    // Aquí iría la lógica de envío del formulario (puedes hacer un POST a tu servidor)
                }
            }
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
</body>
</html>
