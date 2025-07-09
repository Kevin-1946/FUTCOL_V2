Algoritmo reloj
	Definir opcreloj Como Entero;
	Definir horario, minutero, segundero Como Entero;
	Escribir "Menú de opciones"; Escribir "";
	Escribir"1. Horario - Minutero";
	Escribir"2. Minutero - Segundero";
	Escribir"3. Horario - Segundero";
	Escribir"4. Minutero - Horario";
	Escribir"5. Salir";
	Escribir ""; Escribir "Selecciona una opción por favor" Sin Saltar;
	Leer opcreloj;
	Segun opcreloj Hacer
		1:
		   Escribir "Ingrese la hora" Sin Saltar;
		   Leer horario;
		   Escribir "Ingrese el minuto" Sin Saltar;
		   Leer minutero;
		   rh=horario*30
		   rm=minutero*6
		   Si rm>rh Entonces
			   angulo1=rm-rh
			   Escribir "El ángulo entre las ", horario " y ", minutero " es ", angulo1, " grados.";
		   SiNo
			   angulo11=360-(rh-rm)
			   Escribir "El ángulo entre las ", horario " y ", minutero " es ", angulo11, " grados.";
		   FinSi
		2:
		   Escribir "Ingrese el minuto" Sin Saltar;
		   Leer minutero;
		   Escribir "Ingrese el segundo" Sin Saltar;
		   Leer segundero;
		   rm=minutero*6
		   rs=segundero*6
		   Si rs>rm Entonces
			   angulo2=rs-rm
			   Escribir "El ángulo entre el minuto ", minutero " y el segundo ", segundero " es ", angulo2, " grados.";
		   SiNo
			   angulo22=360-(rm-rs)
			   Escribir "El ángulo entre el minuto ", minutero " y el segundo ", segundero " es ", angulo22, " grados.";
		   FinSi
		   
	   3:
		   Escribir "Ingrese la hora" Sin Saltar;
		   Leer horario;
		   Escribir "Ingrese el segundo" Sin Saltar;
		   Leer segundero;
		   rh=horario*30
		   rs=segundero*6
		   Si rs>rh Entonces
			   angulo3=rs-rh
			   Escribir "El ángulo entre las ", horario " con ", segundero " segundos es ", angulo3, " grados.";
		   SiNo
			   angulo33=360-(rh-rs)
			   Escribir "El ángulo entre las ", horario " con ", segundero " segundos es ", angulo33, " grados.";
		   FinSi
	   4:
		   Escribir "Ingrese el minuto" Sin Saltar;
		   Leer minutero;
		   Escribir "Ingrese la hora" Sin Saltar;
		   Leer horario;
		   rm=minutero*6
		   rh=horario*30
		   Si rh>rm Entonces
			   angulo4=rh-rm
			   Escribir "El ángulo entre el minuto ", minutero " y la hora ", horario " es de ", angulo4, " grados.";
		   SiNo
			   angulo44=360-(rm-rh)
			   Escribir "El ángulo entre el minuto ", minutero " y la hora ", horario " es de ", angulo44, " grados.";
		   FinSi
	   5: 
		   Escribir "¡NOS VEMOS!";
	FinSegun
   FinAlgoritmo
