<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
 public function up(): void {
  Schema::create('employees', function(Blueprint $t){$t->id();$t->string('name');$t->timestamps();});
  Schema::create('shifts', function(Blueprint $t){$t->id();$t->string('employee');$t->date('date');$t->string('kind');$t->string('start');$t->string('end');$t->string('color');$t->timestamps();});
  Schema::create('shift_templates', function(Blueprint $t){$t->id();$t->string('name');$t->string('kind');$t->string('start');$t->string('end');$t->string('color');$t->timestamps();});
  foreach(['Анна Смирнова','Иван Петров','Мария Кузнецова','Алексей Орлов','Ольга Волкова'] as $name) DB::table('employees')->insert(['name'=>$name,'created_at'=>now(),'updated_at'=>now()]);
  DB::table('shift_templates')->insert(['name'=>'Дневная смена','kind'=>'Дневная','start'=>'10:00','end'=>'19:00','color'=>'green','created_at'=>now(),'updated_at'=>now()]);
  DB::table('shifts')->insert([['employee'=>'Анна Смирнова','date'=>'2024-06-03','kind'=>'Утренняя','start'=>'09:00','end'=>'18:00','color'=>'blue'],['employee'=>'Иван Петров','date'=>'2024-06-04','kind'=>'Вечерняя','start'=>'14:00','end'=>'23:00','color'=>'purple']]);
 }
 public function down(): void {Schema::dropIfExists('shift_templates');Schema::dropIfExists('shifts');Schema::dropIfExists('employees');}
};
