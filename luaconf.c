#include "luaconf.h"

hash_table ht;

lua_State * createLS(char *file)
{
        char path[256];
        sprintf(path,"./script/%s",file);
        lua_State * L;
        L = luaL_newstate();
        luaL_openlibs(L);
        luaL_dofile(L, path);
        printf("LUA=> %s has been loaded.\r\n",path);
        lua_register(L,"jsonParse",jsonParse);
        return L;
}


void iniLuaStates()
{
        DIR    *dir;
        struct    dirent    *ptr;
        dir = opendir("./script/");
	ht_init(&ht, HT_KEY_CONST | HT_VALUE_CONST, 0.05);
	
        while((ptr = readdir(dir)) != NULL)
        {
                if(strcmp(".",ptr->d_name)!=0&&strcmp("..",ptr->d_name)){
			lua_ptr_entry *lpe=(lua_ptr_entry *)malloc(sizeof(lua_ptr_entry));
			lpe->LS = createLS(ptr->d_name);
			ht_insert(&ht, ptr->d_name, strlen(ptr->d_name)+1, lpe, sizeof(lua_ptr_entry));
		}	
        }

        closedir(dir);
}


lua_State * findState(const char *file)
{
        char path[128] ;
        sprintf(path,"%s.lua",file);
	lua_ptr_entry* lpe =(lua_ptr_entry *)ht_get(&ht,path,strlen(path)+1,NULL);
	printf("%p\r\n",lpe);
	if(lpe){
		return lpe->LS;
	}
	return NULL;
}

static int jsonParse(lua_State *L)
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


