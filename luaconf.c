#include "luaconf.h"

hash_table ht;

void fread_lua(char *file,lua_ptr_entry *plpe)
{
	char path[256];
        sprintf(path,"./script/%s",file);
	lua_State *L = luaL_newstate();
	luaL_openlibs(L);
	int error = luaL_loadfile(L,path);
	plpe->name = file;
	plpe->L = L;
	return ;

}

void init_lua_buffer()
{
        DIR    *dir;
        struct    dirent    *ptr;
        dir = opendir("./script/");
	ht_init(&ht, HT_KEY_CONST | HT_VALUE_CONST, 0.05);
        while((ptr = readdir(dir)) != NULL)
        {
                if(strcmp(".",ptr->d_name)!=0&&strcmp("..",ptr->d_name)){
			lua_ptr_entry *lpe;
			//fread_lua(ptr->d_name,&lpe);
			lpe = (lua_ptr_entry *)malloc(sizeof(lua_ptr_entry));
			char path[256];
	        	sprintf(path,"./script/%s",ptr->d_name);
        		lua_State *L = luaL_newstate();
        		luaL_openlibs(L);
        		int error = luaL_loadfile(L,path)||lua_pcall(L,0,0,0);
        		lpe->name = ptr->d_name;
        		lpe->L = L;
			ht_insert(&ht, ptr->d_name, strlen(ptr->d_name)+1, lpe, sizeof(lua_ptr_entry));
		}	
        }

        closedir(dir);
}

int jsonParse(lua_State *L)
{
        int i =0;
        const char * json = luaL_checkstring(L,1);
        JSON_Value *schema = json_parse_string(json);
        JSON_Array *commits = json_value_get_array(schema);
        int count = json_array_get_count(commits);
        for (i = 0; i < count; i++)
        {
                const char * value = json_array_get_string(commits,i);
                lua_pushstring(L, value);
        }
        return count;
}

lua_State * new_state(char * file){
	size_t val_s;
	lua_ptr_entry* lpe =(lua_ptr_entry *)ht_get(&ht,file,strlen(file)+1,&val_s);
	return lpe->L;
        //int error = luaL_loadbuffer(*L,lpe->buffer,strlen(lpe->buffer),file)||lua_pcall(*L, 0, 0, 0);
}
