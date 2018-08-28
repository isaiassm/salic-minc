import * as actions from './actions';
import * as projetoHelperAPI from '@/helpers/api/Projeto';
import axios from 'axios';

jest.mock('axios');

describe('Projeto actions', () => {
    let commit;
    let mockReponse;

    describe('buscaProjeto', () => {
        beforeEach(() => {
            mockReponse = {
                data: {
                    data: {
                        projeto: {
                            IdPRONAC: '132451',
                            Item: 'Hospedagem sem Alimenta\xE7\xE3o',
                            NomeProjeto: 'Crian\xE7a Para Vida - 15 anos',
                        },
                    },
                },
            };

            axios.get.mockResolvedValue(mockReponse);

            commit = jest.fn();
            jest.spyOn(projetoHelperAPI, 'buscaProjeto');
            actions.buscaProjeto({ commit });
        });

        test('it calls projetoHelperAPI.buscaProjeto', () => {
            expect(projetoHelperAPI.buscaProjeto).toHaveBeenCalled();
        });

        test('it is commit to buscaProjeto', (done) => {
            const projeto = mockReponse.data;
            done();
            expect(commit).toHaveBeenCalledWith('SET_PROJETO', projeto.data);
        });
    });

    describe('buscaProponente', () => {
        beforeEach(() => {
            mockReponse = {
                data: {
                    data: {
                        proponente: {
                            Proponente: 'Associa\xE7\xC3o Beneficiente Cultural Religiosa Centro Judaico do Brooklin',
                            idAgente: '24806',
                            TipoPessoa: 'Jur\xCDdica',
                        },
                    },
                },
            };

            axios.get.mockResolvedValue(mockReponse);

            commit = jest.fn();
            jest.spyOn(projetoHelperAPI, 'buscaProponente');
            actions.buscaProponente({ commit });
        });

        test('it calls projetoHelperAPI.buscaProponente', () => {
            expect(projetoHelperAPI.buscaProponente).toHaveBeenCalled();
        });

        test('it is commit to buscaProponente', (done) => {
            const proponente = mockReponse.data;
            done();
            expect(commit).toHaveBeenCalledWith('SET_PROPONENTE', proponente.data);
        });
    });

    describe('buscaPlanilhaHomologada', () => {
        beforeEach(() => {
            mockReponse = {
                data: {
                    data: {
                        planilhaHomologada: {
                            tpPlanilha: 'CO',
                            IdPronac: '189786',
                            PRONAC: '150151',
                        },
                    },
                },
            };

            axios.get.mockResolvedValue(mockReponse);

            commit = jest.fn();
            jest.spyOn(projetoHelperAPI, 'buscaPlanilhaHomologada');
            const idPronac = 123456;
            actions.buscaPlanilhaHomologada({ commit }, idPronac);
        });

        test('it calls projetoHelperAPI.buscaPlanilhaHomologada', () => {
            expect(projetoHelperAPI.buscaPlanilhaHomologada).toHaveBeenCalled();
        });

        test('it is commit to buscaPlanilhaHomologada', (done) => {
            const planilhaHomologada = mockReponse.data;
            done();
            expect(commit).toHaveBeenCalledWith('SET_PLANILHA_HOMOLOGADA', planilhaHomologada.data);
        });
    });
});
