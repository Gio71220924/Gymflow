<?php                                                                                                                                                                           
                                                                                                                                                                                  
  namespace App;                                                                                                                                                                  
                                                                                                                                                                                  
  use Illuminate\Contracts\Auth\MustVerifyEmail;                                                                                                                                  
  use Illuminate\Foundation\Auth\User as Authenticatable;                                                                                                                         
  use Illuminate\Notifications\Notifiable;                                                                                                                                        
                                                                                                                                                                                  
  class User extends Authenticatable                                                                                                                                              
  {                                                                                                                                                                               
      use Notifiable;                                                                                                                                                             
                                                                                                                                                                                  
      const ROLE_USER = 'user';                                                                                                                                                   
      const ROLE_SUPER_ADMIN = 'super_admin';                                                                                                                                     
      const STATUS_ACTIVE = 'active';                                                                                                                                             
      const STATUS_INACTIVE = 'inactive';                                                                                                                                         
                                                                                                                                                                                  
      /**                                                                                                                                                                         
       * The attributes that are mass assignable.                                                                                                                                 
       *
       * @var array                                                                                                                                                               
       */                                                                                                                                                                         
      protected $fillable = [                                                                                                                                                     
          'name', 'email', 'password', 'role', 'status',                                                                                                                          
      ];                                                                                                                                                                          
                                                                                                                                                                                  
      /**                                                                                                                                                                         
       * The attributes that should be hidden for arrays.                                                                                                                         
       *                                                                                                                                                                          
       * @var array                                                                                                                                                               
       */                                                                                                                                                                         
      protected $hidden = [                                                                                                                                                       
          'password', 'remember_token',                                                                                                                                           
      ];                                                                                                                                                                          
                                                                                                                                                                                  
      /**                                                                                                                                                                         
       * The attributes that should be cast to native types.                                                                                                                      
       *                                                                                                                                                                          
       * @var array                                                                                                                                                               
       */                                                                                                                                                                         
      protected $casts = [                                                                                                                                                        
          'email_verified_at' => 'datetime',                                                                                                                                      
      ];                                                                                                                                                                          
                                                                                                                                                                                  
      public function memberGym()                                                                                                                                                 
      {                                                                                                                                                                           
          return $this->hasOne(Member_Gym::class, 'user_id');                                                                                                                     
      }
                                                                                                                                                                                  
      public function isSuperAdmin()                                                                                                                                              
      {                                                                                                                                                                           
          return $this->role === self::ROLE_SUPER_ADMIN;                                                                                                                          
      }                                                                                                                                                                           
  }                        
