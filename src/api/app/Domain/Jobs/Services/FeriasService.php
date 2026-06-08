<?php

namespace App\Domain\Jobs\Services;

use App\Domain\Jobs\Contracts\ServiceInterface;
use App\Domain\Jobs\DTOs\FeriasDTO;
use App\Domain\Jobs\Models\Ferias;
use App\Domain\Jobs\Repositories\FeriasRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class FeriasService implements ServiceInterface
{

	protected $feriasRepository;

	public function __construct(FeriasRepository $feriasRepository)
	{
		$this->feriasRepository = $feriasRepository;
	}

	public function search(Request $request): Collection
	{
		return $this->feriasRepository->search($request->all());
	}

	public function create(Request $request): Ferias
	{
		$request->merge([ 'usuario' => auth()->user() ]);
		$dto = FeriasDTO::fromRequest($request);
		return $this->feriasRepository->createWithUser($dto);
	}

	public function find(string $id): ?Ferias
	{
		return $this->feriasRepository->find($id);
	}

	public function update($id, Request $request): bool
	{
		$request->merge([ 'usuario' => auth()->user() ]);
		$dto = FeriasDTO::fromRequest($request);
		return $this->feriasRepository->update($id, $dto->toArray());
	}

	public function delete($id): bool
	{
		return $this->feriasRepository->delete($id);
	}

	public function verifyDiasAteFerias(array $data)
	{
		$retorno = null;
		$ultimaFeriasAgendada = $this->feriasRepository->getUltimaFeriasAgenda($data);
		$hoje = Carbon::today();

		if(!empty($ultimaFeriasAgendada) && ($ultimaFeriasAgendada->ativo || $ultimaFeriasAgendada->inicio->greaterThan($hoje))) {
			$diferencaEmDiasInicio = $hoje->diffInDays($ultimaFeriasAgendada->inicio);
			$diferencaEmDiasRetorno = $hoje->diffInDays($ultimaFeriasAgendada->fim);
			$retorno = [
				'diasAteFerias' => $diferencaEmDiasInicio,
				'diasAteRetorno' => $diferencaEmDiasRetorno,
				'ativo' => $ultimaFeriasAgendada->ativo==1
			];
		}
		
		return $retorno;
	}
}
