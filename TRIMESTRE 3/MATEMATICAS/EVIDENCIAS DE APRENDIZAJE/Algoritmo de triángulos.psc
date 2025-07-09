Algoritmo Tarea
	Escribir "***********¡Hola!***********"; Escribir ""; 
	Escribir "Te ayudaré con tu triángulo"; Escribir "";
    Definir lado1, lado2, lado3 Como Real;
    Escribir "Digita el primer lado del triángulo:";
    Leer lado1;
    Escribir "Digita el segundo lado del triángulo:";
    Leer lado2;
    Escribir "Digita el tercer lado del triángulo:";
    Leer lado3;
    Si lado1 = lado2 y lado2 = lado3 Entonces;
        Escribir "Tú triángulo es equilátero.";
    Sino
        Si lado1 = lado2 o lado1 = lado3 o lado2 = lado3 Entonces;
            Escribir "Tú triángulo es isósceles.";
        Sino
            Escribir "Tú triángulo es escaleno.";
        FinSi
    FinSi
FinAlgoritmo