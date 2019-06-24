<?php

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Event;
use App\Models\Address;
use App\Models\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PresentationSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userNames = [
            'Joao da Silva',
            'Alice Izidoro',
            'Arthur Menarim',
            'Heitor Xavier',
            'Valentina Bergamasco',
            'Calixto Antônio',
            'Salim André',
            'Vitoria Azuma',
            'Brenda Gilliette',
            'Giovany Bergamasco'
        ];

        $addresses = [
            [ 'name' => 'Endereço 1', 'street' => 'Rua Vicente Albertino Marchalek', 'number' => 158, 'zip_code' => '81250-690', 'neighborhood' => 'Fazendinha', 'complement' => 'Sobrado de Trás', 'city_id' => 2878],
            [ 'name' => 'Endereço 2', 'street' => 'Rua Eliezer Disaró Fangueiro', 'number' => 993, 'zip_code' => '81540-440', 'neighborhood' => 'Uberaba', 'complement' => '', 'city_id' => 2878],
            [ 'name' => 'Endereço 3', 'street' => 'Rua Edson Antônio Ramos', 'number' => 634, 'zip_code' => '81830-045', 'neighborhood' => 'Xaxim', 'complement' => '', 'city_id' => 2878],
            [ 'name' => 'Endereço 4', 'street' => 'Rua Largo Otto Braun', 'number' => 485, 'zip_code' => '80540-090', 'neighborhood' => 'Ahú', 'complement' => '', 'city_id' => 2878],
            [ 'name' => 'Endereço 5', 'street' => 'Rua Travessa General Francisco Lima e Silva', 'number' => 136, 'zip_code' => '80520-040', 'neighborhood' => 'São Francisco', 'complement' => '', 'city_id' => 2878],
            [ 'name' => 'Endereço 6', 'street' => 'Rua Flávio Dallegrave', 'number' => 908, 'zip_code' => '82540-014', 'neighborhood' => 'Boa Vista', 'complement' => '', 'city_id' => 2878],
            [ 'name' => 'Endereço 7', 'street' => 'Rua Professor Elias Zacharias', 'number' => 272, 'zip_code' => '81220-306', 'neighborhood' => 'Campo Comprido', 'complement' => '', 'city_id' => 2878],
            [ 'name' => 'Endereço 8', 'street' => 'Rua Ricardo Carta', 'number' => 108, 'zip_code' => '80310-070', 'neighborhood' => 'Seminário', 'complement' => '', 'city_id' => 2878],
            [ 'name' => 'Endereço 9', 'street' => 'Rua Giacomo Mylla', 'number' => 482, 'zip_code' => '80520-150', 'neighborhood' => 'Bom Retiro', 'complement' => '', 'city_id' => 2878],
            [ 'name' => 'Endereço 10', 'street' => 'Rua Padre José Lopacinski', 'number' => 641, 'zip_code' => '81280-080', 'neighborhood' => 'Cidade Industrial', 'complement' => '', 'city_id' => 2878]
        ];

        $notification = Notification::create(['text' => 'Bem-vindo ao EVENTA! Comece a navegar pelos eventos e crie um para vocẽ agora mesmo!']);

        $userIds = [];

        for ($i = 0; $i < 10; $i++) {

            $address = Address::create($addresses[$i]);

            $user = User::create([
                'name' => $userNames[$i],
                'email' => explode(' ', strtolower($userNames[$i]))[0] . '@email.com',
                'password' => '123456',
                'nickname' => explode(' ', $userNames[$i])[0],
                'is_active' => true,
                'is_admin' => false,
                'address_id' => $address->id
            ]);

            array_push($userIds, $user->id);
            UserProfile::create([ 'user_id' => $user->id ]);
            $user->notifications()->save($notification);
        }

        $address = Address::create([
            'name' => 'Minha Casa', 'street' => 'Rua Adão Sobocinski', 'number' => 161, 'zip_code' => '80050-480', 'neighborhood' => 'Cristo Rei', 'complement' => 'Ap. 601', 'city_id' => 2878
        ]);

        $user = User::create([
            'name' => 'Jhonny Izidoro Menarim',
            'email' => 'jhonny@email.com',
            'password' => '123456',
            'nickname' => 'Jhonny',
            'is_active' => true,
            'is_admin' => true,
            'address_id' => $address->id
        ]);

        array_push($userIds, $user->id);
        UserProfile::create([ 'user_id' => $user->id ]);
        $user->notifications()->save($notification);

        $address = Address::create([
            'name' => 'Minha Casa', 'street' => 'Rua Vicente Albertino Marchalek', 'number' => 158, 'zip_code' => '81250-690', 'neighborhood' => 'Fazendinha', 'complement' => 'Sobrado de Trás', 'city_id' => 2878
        ]);

        $user = User::create([
            'name' => 'Matheus Bergamasco Xavier',
            'email' => 'matheus@email.com',
            'password' => '123456',
            'nickname' => 'Matheus',
            'is_active' => true,
            'is_admin' => true,
            'address_id' => $address->id
        ]);

        array_push($userIds, $user->id);
        UserProfile::create([ 'user_id' => $user->id ]);
        $user->notifications()->save($notification);

        foreach ($userIds as $id) {
            for ($i = 1; $i <= 12; $i++) {
                $user = User::find($id);

                if ($i != $user->id) {
                    $user->followings()->save(User::find($i));
                }
            }
        }
    }
}
