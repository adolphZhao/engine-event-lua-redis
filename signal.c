#include <stdlib.h>
#include <stdio.h>
#include <signal.h>

void signal_handle(int signal_num){
	switch(signal_num)
	{
		case SIGINT :
			printf("%d\r\n",signal_num);
			exit(0);
			break;
		case SIGQUIT :
			printf("%d\r\n",signal_num);
                        exit(0);
			break;
		case SIGSTOP :
		case SIGTERM :
		case SIGKILL :
			printf("%d\r\n",signal_num);
		break;
		default :
			printf("%d\r\n",signal_num);
			exit(100);
	}
}

int main (){
	signal(SIGINT,signal_handle);
	signal(SIGQUIT,signal_handle);
	signal(SIGSTOP,signal_handle);
	signal(SIGTERM,signal_handle);
	signal(SIGKILL,signal_handle);
	while(1){

	};
}
