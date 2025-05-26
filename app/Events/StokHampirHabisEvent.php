use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Barang;

class StokHampirHabisEvent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $barang;

    public function __construct($barang)
    {
        $this->barang = $barang;
    }

    public function broadcastOn()
    {
        return ['stok-hampir-habis'];
    }

    public function broadcastAs()
    {
        return 'stokHampirHabis';
    }
}
