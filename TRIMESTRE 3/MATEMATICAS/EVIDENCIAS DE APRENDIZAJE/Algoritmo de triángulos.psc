Algoritmo Tarea
	Escribir "***********�Hola!***********"; Escribir ""; 
	Escribir "Te ayudar� con tu tri�ngulo"; Escribir "";
    Definir lado1, lado2, lado3 Como Real;
    Escribir "Digita el primer lado del tri�ngulo:";
    Leer lado1;
    Escribir "Digita el segundo lado del tri�ngulo:";
    Leer lado2;
    Escribir "Digita el tercer lado del tri�ngulo:";
    Leer lado3;
    Si lado1 = lado2 y lado2 = lado3 Entonces;
        Escribir "T� tri�ngulo es equil�tero.";
    Sino
        Si lado1 = lado2 o lado1 = lado3 o lado2 = lado3 Entonces;
            Escribir "T� tri�ngulo es is�sceles.";
        Sino
            Escribir "T� tri�ngulo es escaleno.";
        FinSi
    FinSi
FinAlgoritmo